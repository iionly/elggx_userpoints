<?php
/**
 * Support for Invite Friends plugin
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

if (!elgg_is_active_plugin('invitefriends')) {
	return;
}

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$title = elgg_echo('userpoints_standard:invitesettings');

$content = elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:invite'),
	'name' => 'params[invite]',
	'value' => $plugin->invite,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('userpoints_standard:verify_email'),
	'name' => 'params[verify_email]',
	'value' => $plugin->verify_email,
	'options_values' => [
		'1' => elgg_echo('option:yes'),
		'0' => elgg_echo('option:no'),
	],
]);

$content .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('userpoints_standard:require_registration'),
	'name' => 'params[require_registration]',
	'value' => $plugin->require_registration,
	'options_values' => [
		'1' => elgg_echo('option:yes'),
		'0' => elgg_echo('option:no'),
	],
]);

$content .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('userpoints_standard:expire_invite'),
	'name' => 'params[expire_invite]',
	'value' => $plugin->expire_invite,
	'options_values' => [
		'0' => elgg_echo('elggx_userpoints:settings:never'),
		'3600' => elgg_echo('elggx_userpoints:settings:1_hour'),
		'86400' => elgg_echo('elggx_userpoints:settings:1_day'),
		'604800' => elgg_echo('elggx_userpoints:settings:1_week'),
		'1209600' => elgg_echo('elggx_userpoints:settings:2_weeks'),
		'2419200' => elgg_echo('elggx_userpoints:settings:4_weeks'),
		'31536000' => elgg_echo('elggx_userpoints:settings:365_days'),
	],
]);

echo elgg_view_module('inline', $title, $content);
