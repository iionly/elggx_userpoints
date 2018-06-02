<?php
/**
 * Elggx Userpoints Plugin
 *
 * Index page Toppoints widget for Widget Manager plugin
 *
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$count = (int) $widget->toppoints_count;
if ($count < 1) {
	$count = 10;
}

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('elggx_userpoints:settings:toppoints:num'),
	'name' => 'params[toppoints_count]',
	'value' => $count,
	'min' => 1,
	'max' => 25,
	'step' => 1,
]);
