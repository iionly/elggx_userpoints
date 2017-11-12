<?php
/**
 * Support for Poll plugin
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

if (!elgg_is_active_plugin('poll')) {
	return;
}

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$title = elgg_echo('userpoints_standard:polls');

$content = elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:poll'),
	'name' => 'params[poll]',
	'value' => $plugin->poll,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:pollvote'),
	'name' => 'params[vote]',
	'value' => $plugin->vote,
	'min' => 0,
	'step' => 1,
]);

echo elgg_view_module('inline', $title, $content);
