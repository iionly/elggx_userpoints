<?php

$all_users = elgg_get_entities([
	'type' => 'user',
	'limit' => false,
	'batch' => true,
]);

/* @var $user \ElggUser */
foreach ($all_users as $user) {

	elgg_delete_metadata([
		'guid' => $user->guid,
		'metadata_name' => 'userpoints_points',
		'limit' => false,
	]);

	$users_points = elggx_userpoints_get($user->guid);
	$users_approved_points = $users_points['approved'];
	$user->userpoints_points = (int) $users_approved_points;
}

return elgg_ok_response('', elgg_echo('elggx_userpoints:restore_all:success'), REFERER);
