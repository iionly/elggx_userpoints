<?php
/**
 * General points, mostly for default Elgg actions
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$title = elgg_echo('userpoints_standard:activities');

$content = elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:friend'),
	'name' => 'params[friend]',
	'value' => $plugin->friend,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:blog'),
	'name' => 'params[blog]',
	'value' => $plugin->blog,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:file'),
	'name' => 'params[file]',
	'value' => $plugin->file,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:bookmarks'),
	'name' => 'params[bookmarks]',
	'value' => $plugin->bookmarks,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:comment'),
	'name' => 'params[comment]',
	'value' => $plugin->comment,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:thewire'),
	'name' => 'params[thewire]',
	'value' => $plugin->thewire,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:group'),
	'name' => 'params[group]',
	'value' => $plugin->group,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:discussion'),
	'name' => 'params[discussion]',
	'value' => $plugin->discussion,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:messageboard'),
	'name' => 'params[messageboard]',
	'value' => $plugin->messageboard,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:page_top'),
	'name' => 'params[page_top]',
	'value' => $plugin->page_top,
	'min' => 0,
	'step' => 1,
]);

$content .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('userpoints_standard:likes'),
	'name' => 'params[likes]',
	'value' => $plugin->likes,
	'min' => 0,
	'step' => 1,
]);

echo elgg_view_module('inline', $title, $content);
