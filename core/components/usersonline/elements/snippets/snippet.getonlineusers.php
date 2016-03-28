<?php

    /** @var array $scriptProperties */
    /** @var UsersOnline $UsersOnline */
    if (!$UsersOnline = $modx->getService('usersonline', 'UsersOnline', $modx->getOption('usersonline_core_path', null,
            $modx->getOption('core_path') . 'components/usersonline/') . 'model/usersonline/', $scriptProperties)
    ) {
        return 'Could not load UsersOnline class!';
    }
    if (!$pdo = $modx->getService('pdoTools')) {
        return $modx->lexicon('no_pdo');
    }
    $interval = $modx->getOption('timeInterval', $scriptProperties, -1);
    if ($interval == -1) {
        $interval = $modx->getOption('usersonline_time_span');
    }
    $contexts = $modx->getOption('contexts', $scriptProperties, null);
    $innerJoin = $modx->getOption('innerJoin', $scriptProperties, '');
    $innerJoin = $modx->fromJSON($innerJoin);
    $innerJoin['UsersOnline'] = array(
        'class' => 'userOnline',
        'on'    => 'modUser.id = UsersOnline.user_id',
    );
    $select = $modx->getOption('select', $scriptProperties, '');
    $select = $modx->fromJSON($select);
    $select['UsersOnline'] = '*';
    $time = time();
    $startTime = $time - $interval;
    $where = $modx->getOption('where', $scriptProperties, '');
    $where = $modx->fromJSON($where);
    $where[] = array(
        'UsersOnline.lastvisit:>=' => $startTime,
        'UsersOnline.lastvisit:<=' => $time,
    );
    $contextsArray = array();
    if ($contexts != null) {
        $contextsArray = explode(',', $contexts);
    }
    if (!empty($contextsArray)) {
        $where[] = array(
            'UsersOnline.context_key:IN' => $contextsArray,
        );
    }
    $scriptProperties['where'] = $modx->toJSON($where);
    $scriptProperties['innerJoin'] = $modx->toJSON($innerJoin);
    $scriptProperties['select'] = $modx->toJSON($select);
    $output = $modx->runSnippet('pdoUsers', $scriptProperties);
    return $output;