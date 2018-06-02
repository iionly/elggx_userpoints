<?php
/**
 * Point for profile edit actions
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$title = elgg_echo('userpoints_standard:profile_settings');

$content = elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:profile'),
	'name' => 'params[profileupdate]',
	'value' => $plugin->profileupdate,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('userpoints_standard:profileupdate_threshold'),
	'name' => 'params[profileupdate_threshold]',
	'value' => $plugin->profileupdate_threshold,
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
	'#label' => elgg_echo('userpoints_standard:profileicon'),
	'name' => 'params[profileicon]',
	'value' => $plugin->profileicon,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('userpoints_standard:profileiconupdate_threshold'),
	'name' => 'params[profileiconupdate_threshold]',
	'value' => $plugin->profileiconupdate_threshold,
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

echo elgg_view_module('inline', $title, $content);
