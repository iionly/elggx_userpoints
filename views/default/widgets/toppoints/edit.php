<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $entity->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('elggx_userpoints:settings:toppoints:num'),
	'name' => 'params[num_display]',
	'value' => $num_display,
]);
