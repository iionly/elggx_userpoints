<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 10;
}

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('elggx_userpoints:settings:toppoints:num'),
	'name' => 'params[num_display]',
	'value' => $num_display,
	'min' => 1,
	'max' => 25,
	'step' => 1,
]);
