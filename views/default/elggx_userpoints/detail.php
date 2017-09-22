<?php

echo elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => Userpoint::SUBTYPE,
	'owner_guid' => get_input('user_guid'),
	'metadata_name_value_pairs' => [
		'meta_moderate' => 'approved',
	],
	'no_results' => elgg_echo('elggx_userpoints:approved_empty'),
]);
