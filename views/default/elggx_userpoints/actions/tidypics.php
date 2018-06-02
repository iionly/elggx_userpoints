<?php
/**
 * Support for Tidypics plugin
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

if (!elgg_is_active_plugin('tidypics')) {
	return;
}

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$title = elgg_echo('userpoints_standard:tidypics');

$content = elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:create_album'),
	'name' => 'params[album]',
	'value' => $plugin->album,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:upload_photo'),
	'name' => 'params[image]',
	'value' => $plugin->image,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:phototag'),
	'name' => 'params[phototag]',
	'value' => $plugin->phototag,
	'min' => 0,
	'step' => 1,
]);

echo elgg_view_module('inline', $title, $content);
