<?php
/**
 * Support for iZap videos
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

if (!elgg_is_active_plugin('izap_videos')) {
	return;
}

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$title = elgg_echo('userpoints_standard:izap_videos');

$content = elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:add_video'),
	'name' => 'params[izap_videos]',
	'value' => $plugin->izap_videos,
	'min' => 0,
	'step' => 1,
]);

echo elgg_view_module('inline', $title, $content);
