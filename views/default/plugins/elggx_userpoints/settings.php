<?php
/**
 * Userpoints settings form
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

// show navigation tabs
echo elgg_view('elggx_userpoints/tabs', [
	'tab' => 'settings',
]);

echo elgg_format_element('div', ['class' => 'mvl'], elgg_view('output/url', [
	'href' => 'action/elggx_userpoints/reset',
	'text' => elgg_echo('elggx_userpoints:reset:all'),
	'is_action' => true,
	'is_trusted' => true,
	'confirm' => elgg_echo('elggx_userpoints:reset:all:confirm'),
	'class' => 'elgg-button elgg-button-action',
]));

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('elggx_userpoints:settings:transaction_status'),
	'#help' => elgg_echo('elggx_userpoints:settings:transaction_status_info'),
	'name' => 'params[moderate]',
	'options_values' => [
		'1' => elgg_echo('elggx_userpoints:settings:moderated'),
		'0' => elgg_echo('elggx_userpoints:settings:approved'),
	],
	'value' => $plugin->moderate,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('elggx_userpoints:settings:profile_display'),
	'name' => 'params[profile_display]',
	'options_values' => [
		'1' => elgg_echo('option:yes'),
		'0' => elgg_echo('option:no'),
	],
	'value' => $plugin->profile_display,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('elggx_userpoints:settings:displaymessage'),
	'name' => 'params[displaymessage]',
	'options_values' => [
		'1' => elgg_echo('option:yes'),
		'0' => elgg_echo('option:no'),
	],
	'value' => $plugin->displaymessage,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('elggx_userpoints:settings:subtract'),
	'name' => 'params[subtract]',
	'options_values' => [
		'1' => elgg_echo('option:yes'),
		'0' => elgg_echo('option:no'),
	],
	'value' => $plugin->subtract,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('elggx_userpoints:settings:delete'),
	'name' => 'params[delete]',
	'options_values' => [
		'1' => elgg_echo('option:yes'),
		'0' => elgg_echo('option:no'),
	],
	'value' => $plugin->delete,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('elggx_userpoints:settings:expire_after'),
	'#help' => elgg_echo('elggx_userpoints:settings:expire_after:help'),
	'name' => 'params[expire_after]',
	'options_values' => [
		'0'        => elgg_echo('elggx_userpoints:settings:never'),
		'3600'     => elgg_echo('elggx_userpoints:settings:1_hour'),
		'86400'    => elgg_echo('elggx_userpoints:settings:1_day'),
		'604800'   => elgg_echo('elggx_userpoints:settings:1_week'),
		'1209600'  => elgg_echo('elggx_userpoints:settings:2_weeks'),
		'2419200'  => elgg_echo('elggx_userpoints:settings:4_weeks'),
		'31536000' => elgg_echo('elggx_userpoints:settings:365_days'),
	],
	'value' => $plugin->expire_after,
]);
