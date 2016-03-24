<?php

$settings = array();

$tmp = array(
	'mgr_check' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'usersonline_main',
	),
	'time_span' => array(
		'xtype' => 'textfield',
		'value' => '900',
		'area' => 'usersonline_main',
	)

);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'usersonline_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
