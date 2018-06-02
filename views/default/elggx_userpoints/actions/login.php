<?php
/**
 * Point for login actions
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$title = elgg_echo('userpoints_standard:loginsettings');

$content = elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:login'),
	'name' => 'params[login]',
	'value' => $plugin->login,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('userpoints_standard:login_threshold'),
	'name' => 'params[login_threshold]',
	'value' => $plugin->login_threshold,
	'options_values' => [
		'3600' => elgg_echo('userpoints_standard:1hour'),
		'14400' => elgg_echo('userpoints_standard:4hours'),
		'28800' => elgg_echo('userpoints_standard:8hours'),
		'43200' => elgg_echo('userpoints_standard:12hours'),
		'86400' => elgg_echo('userpoints_standard:1day'),
		'604800' => elgg_echo('userpoints_standard:1week'),
		'2592000' => elgg_echo('userpoints_standard:1month'),
	],
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:login_interval'),
	'name' => 'params[login_interval]',
	'value' => $plugin->login_interval,
	'min' => 0,
	'step' => 1,
]);

echo elgg_view_module('inline', $title, $content);
