<?php

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'label' => elgg_echo('elggx_userpoints:settings:toppoints:num'),
	'max' => 25,
	'default' => 10,
]);
