<?php

$limit = (int) max(get_input('limit', elgg_get_config('default_limit')), 0);
$offset = (int) max(get_input('offset', 0), 0);

$content = elgg_list_entities([
	'type' => 'user',
	'limit' => $limit,
	'offset' => $offset,
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
	'item_view' => 'elggx_userpoints/list/user',
]);

$title = elgg_echo('members:title:elggx_userpoints');

$body = elgg_view_layout('default', [
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'title' => $title,
	'filter_id' => 'members',
	'filter_value' => 'elggx_userpoints',
]);

echo elgg_view_page($title, $body);