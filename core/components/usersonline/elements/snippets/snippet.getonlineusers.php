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
    if (isset($scriptProperties['contexts'])) {
        $contextsArray = explode(',', $scriptProperties['contexts']);
    }
    if(isset($scriptProperties['innerJoin'])){
        $innerJoin = $modx->fromJSON($scriptProperties['innerJoin']);
    }
    $innerJoin['UsersOnline'] = array(
        'class' => 'userOnline',
        'on'    => 'modUser.id = UsersOnline.user_id',
    );
    if(isset($scriptProperties['select'])){
        $select = $modx->fromJSON($scriptProperties['select']);
    }
    $select['UsersOnline'] = '*';
    $timeSpan = $modx->getOption('usersonline_time_span');
    $time = time();
    $startTime = $time - $timeSpan;
    $where = array();
    if(isset($scriptProperties['where'])){
        $where = $modx->fromJSON($scriptProperties['where']);
    }
    $where[] = array(
        'UsersOnline.lastvisit:>=' => $startTime,
        'UsersOnline.lastvisit:<=' => $time,
    );
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