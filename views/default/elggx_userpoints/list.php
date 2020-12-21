<?php

$offset = (int) get_input('offset');
$limit = 10;

$options = [
	'type' => 'user',
	'count' => true,
	'offset' => $offset,
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
];

$count = elgg_get_entities($options);

if (!empty($count)) {

	echo elgg_view('navigation/pagination', [
		'base_url' => elgg_http_add_url_query_elements('admin/settings/elggx_userpoints', [
			'tab' => 'list',
		]),
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
	]);

	$rows = [];

	$options['count'] = false;
	$entities = elgg_get_entities($options);

	/* @var $entity ElggUser */
	foreach ($entities as $entity) {
		$row = [];

		// name
		$row[] = elgg_format_element('td', ['width' => '50%'], elgg_view('output/url', [
			'text' => $entity->name . ' (' . $entity->username . ')',
			'href' => elgg_http_add_url_query_elements('admin/settings/elggx_userpoints', [
				'tab' =>  'detail',
				'user_guid' => $entity->guid,
			]),
		]));
		// points
		$row[] = elgg_format_element('td', ['width' => '20%'], elgg_view('output/url', [
			'text' => (int) $entity->userpoints_points,
			'href' => elgg_http_add_url_query_elements('admin/settings/elggx_userpoints', [
				'tab' =>  'detail',
				'user_guid' => $entity->guid,
			]),
		]));
		// reset
		$row[] = elgg_format_element('td', ['width' => '10%'], elgg_view('output/url', [
			'text' => elgg_echo('elggx_userpoints:reset'),
			'href' => elgg_http_add_url_query_elements('action/elggx_userpoints/reset', [
				'user_guid' => $entity->guid,
			]),
			'confirm' => elgg_echo('elggx_userpoints:reset:confirm', [$entity->username]),
		]));

		$rows[] = elgg_format_element('tr', [], implode('', $row));
	}

	$header_row = [
		elgg_format_element('th', ['width' => '50%'], elgg_echo('elggx_userpoints:user')),
		elgg_format_element('th', ['width' => '20%'], elgg_echo('elggx_userpoints:upperplural')),
		elgg_format_element('th', ['width' => '10%'], elgg_echo('elggx_userpoints:action')),
	];
	$header = elgg_format_element('tr', [], implode('', $header_row));

	$table_content = elgg_format_element('thead', [], $header);
	$table_content .= elgg_format_element('tbody', [], implode('', $rows));
	
	echo elgg_format_element('table', ['class' => 'elgg-table'], $table_content);
}

echo elgg_view_form('elggx_userpoints/restore');

echo elgg_view('output/longtext', [
	'value' => elgg_echo('elggx_userpoints:restore_all_help'),
]);
echo elgg_view("output/url", [
	'href' => "action/elggx_userpoints/restore_all",
	'text' => elgg_echo('elggx_userpoints:restore_all'),
	'is_action' => true,
	'is_trusted' => true,
	'confirm' => elgg_echo('elggx_userpoints:restore_all:confirm'),
	'class' => 'elgg-button elgg-button-action',
]);
