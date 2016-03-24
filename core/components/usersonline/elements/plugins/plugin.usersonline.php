<?php
    if (!$UsersOnline = $modx->getService('usersonline', 'UsersOnline', $modx->getOption('usersonline_core_path', null,
            $modx->getOption('core_path') . 'components/usersonline/') . 'model/usersonline/', $scriptProperties)
    ) {
        return 'Could not load UsersOnline class!';
    }

    switch ($modx->event->name) {
        case 'OnMODXInit':
            $context = $modx->context->key;
            if (!$mgr = $modx->getOption('usersonline_mgr_check') and $context == 'mgr') {
                break;
            }
            if ($user = $modx->getAuthenticatedUser($context)) {
                $blocked = 0;
                $active = $user->get('active');
                if ($profile = $user->getOne('Profile')) {
                    $blocked = $profile->get('blocked');
                }
                if (!$active and $blocked) {
                    $response = $modx->runProcessor('security/access/flush', array());
                }
                $id = $user->get('id');
                if (!$userOnline = $modx->getObject('userOnline', array('user_id' => $id, 'context_key' => $context))) {
                    $userOnline = $modx->newObject('userOnline', array(
                        'user_id'     => $id,
                        'context_key' => $context
                    ));
                }
                $userOnline->set('lastvisit', time());
                $userOnline->save();
            }
            break;
        default:
            break;
    }