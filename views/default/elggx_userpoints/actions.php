<?php

$plugin = elgg_get_plugin_from_id('elggx_userpoints');

$form_vars = [
	'action' => 'action/plugins/settings/save',
	'class' => 'elgg-form-settings',
];
$body_vars = [
	'entity' => $plugin,
];

echo elgg_view_form('elggx_userpoints/actions', $form_vars, $body_vars);
