<?php
/**
 * Support for ElggX Fivestar plugin
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

if (!elgg_is_active_plugin('elggx_fivestar')) {
	return;
}

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$title = elgg_echo('userpoints_standard:fivestar');

$content = elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:fivestar_vote'),
	'name' => 'params[fivestar]',
	'value' => $plugin->fivestar,
	'min' => 0,
	'step' => 1,
]);

echo elgg_view_module('inline', $title, $content);
