<?php

echo elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => Userpoint::SUBTYPE,
	'metadata_name_value_pairs' => [
		'meta_moderate' => 'pending',
	],
	'no_results' => elgg_echo('elggx_userpoints:moderate_empty'),
]);
