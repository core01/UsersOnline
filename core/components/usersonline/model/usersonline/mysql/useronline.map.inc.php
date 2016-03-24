<?php
    $xpdo_meta_map['userOnline'] = array(
        'package'   => 'usersonline',
        'version'   => '1.1',
        'table'     => 'users_online',
        'extends'   => 'xPDOSimpleObject',
        'fields'    =>
            array(
                'user_id'   => 0,
                'lastvisit' => 0,
            ),
        'fieldMeta' =>
            array(
                'user_id'   =>
                    array(
                        'dbtype'    => 'int',
                        'precision' => '10',
                        'phptype'   => 'integer',
                        'null'      => false,
                        'default'   => 0,
                    ),
                'lastvisit' =>
                    array(
                        'dbtype'    => 'int',
                        'precision' => '20',
                        'phptype'   => 'timestamp',
                        'null'      => false,
                        'default'   => 0,
                    ),
            ),
    );
