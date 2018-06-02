<?php
/**
 * Elggx Userpoints Plugin
 *
 * Index page Toppoints widget for Widget Manager plugin
 *
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

// get widget settings
$limit = (int) $widget->toppoints_count;
if ($limit < 1) {
	$limit = 10;
}

echo elgg_list_entities_from_metadata([
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
