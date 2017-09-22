<?php
/**
 * Support for Recommendations plugin
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

if (!elgg_is_active_plugin('recommendations')) {
	return;
}

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$title = elgg_echo('userpoints_standard:recommendations');

$content = elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:recommendations:points'),
	'name' => 'params[recommendation]',
	'value' => $plugin->recommendation,
	'min' => 0,
]);

$content .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('userpoints_standard:recommendations:approve'),
	'name' => 'params[recommendations_approve]',
	'value' => $plugin->recommendations_approve,
	'options_values' => [
		'1' => elgg_echo('option:yes'),
		'0' => elgg_echo('option:no'),
	],
]);

echo elgg_view_module('inline', $title, $content);

