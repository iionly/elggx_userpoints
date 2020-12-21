<?php

$user_guid = (int) get_input('user_guid');

// Delete all the userpoint objects for the selected user or for all users if user_guid = 0
if ($user_guid > 0) {

	// get all userpoint objects for the selected user and delete them
	$entities = elgg_get_entities([
		'type' => 'object',
		'subtype' => Userpoint::SUBTYPE,
		'owner_guid' => $user_guid,
		'limit' => false,
		'batch' => true,
		'batch_inc_offset' => false,
	]);
	foreach ($entities as $entity) {
		$entity->delete();
	}

	// and remove the userpoints_points metadata for the selected user
	elgg_delete_metadata([
		'guid' => $user_guid,
		'metadata_name' => 'userpoints_points',
		'limit' => false,
	]);

} else {

	// get all userpoint objects of all users and delete them
	$entities = elgg_get_entities([
		'type' => 'object',
		'subtype' => Userpoint::SUBTYPE,
		'limit' => false,
		'batch' => true,
		'batch_inc_offset' => false,
	]);
	foreach ($entities as $entity) {
		$entity->delete();
	}

	// and also remove all userpoints_points metadata
	elgg_delete_metadata([
		'type' => 'user',
		'metadata_name' => 'userpoints_points',
		'limit' => false,
	]);
}

return elgg_ok_response('', elgg_echo('elggx_userpoints:reset:success', [elgg_echo('elggx_userpoints:lowerplural')]), REFERER);
