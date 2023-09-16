<?php
/**
 * Elggx Userpoints Plugin
 *
 * Index page Toppoints widget for Widget Manager plugin
 *
 */

$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->toppoints_count ?: 10;

elgg_push_context('front');
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
elgg_pop_context();
