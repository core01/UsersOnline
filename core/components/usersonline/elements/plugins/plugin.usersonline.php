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
                $id = $user->get('id');
                if (!$userOnline = $modx->getObject('userOnline', array('user_id' => $id, 'context_key' => $context))) {
                    $userOnline = $modx->newObject('userOnline', array(
                        'user_id'     => $id,
                        'context_key' => $context
                    ));
                }
                $userOnline->set('lastvisit', time());
                $userOnline->save();
                if (!$active or $blocked) {
                    $response = $response = $modx->runProcessor('/security/logout');
                    $modx->sendRedirect($modx->makeUrl($modx->getOption('site_start')));
                }
            }
            break;
        default:
            break;
    }