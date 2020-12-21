<?php

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => Userpoint::SUBTYPE,
	'owner_guid' => get_input('user_guid'),
	'metadata_name_value_pairs' => [
		'meta_moderate' => 'approved',
	],
	'item_class' => 'userpoint_details_item',
	'no_results' => elgg_echo('elggx_userpoints:approved_empty'),
]);
