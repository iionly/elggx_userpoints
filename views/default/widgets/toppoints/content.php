<?php

$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->num_display ?: 10;

echo elgg_list_entities([
	'type' => 'user',
	'limit' => $limit,
	'sort_by' => [
		'property' => 'userpoints_points',
		'direction' => 'DESC',
		'signed' => true,
		'property_type' => 'metadata',
	],
	'metadata_name_value_pairs' => [
		'name' => 'userpoints_points',
		'value' => 0,
		'operand' => '>',
	],
	'pagination' => false,
	'item_view' => 'elggx_userpoints/list/user',
]);
