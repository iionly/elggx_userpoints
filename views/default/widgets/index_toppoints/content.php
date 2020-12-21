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
	'order_by_metadata' => [
		'name' => 'userpoints_points',
		'direction' => 'DESC',
		'as' => 'integer',
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
