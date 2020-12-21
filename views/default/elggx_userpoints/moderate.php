<?php

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => Userpoint::SUBTYPE,
	'metadata_name_value_pairs' => [
		'meta_moderate' => 'pending',
	],
	'item_class' => 'userpoint_details_item',
	'no_results' => elgg_echo('elggx_userpoints:moderate_empty'),
]);
