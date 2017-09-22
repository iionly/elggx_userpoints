<?php
/**
 * Support for Classified plugin
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

if (!elgg_is_active_plugin('classifieds')) {
	return;
}

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$title = elgg_echo('userpoints_standard:classifieds');

$content = elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:add_classified'),
	'name' => 'params[ad]',
	'value' => $plugin->ad,
	'min' => 0,
]);

echo elgg_view_module('inline', $title, $content);
