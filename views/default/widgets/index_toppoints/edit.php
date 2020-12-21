<?php
/**
 * Elggx Userpoints Plugin
 *
 * Index page Toppoints widget for Widget Manager plugin
 *
 */

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'name' => 'toppoints_count',
	'label' => elgg_echo('elggx_userpoints:settings:toppoints:num'),
	'max' => 25,
	'default' => 10,
]);
