<?php

$username = get_input('username');
$user = get_user_by_username($username);

if (!($user instanceof ElggUser)) {
	return elgg_error_response(elgg_echo('elggx_userpoints:error:invalid_username', [$username]));
}

elgg_delete_metadata([
	'guid' => $user->guid,
	'metadata_name' => 'userpoints_points',
	'limit' => false,
]);

$users_points = elggx_userpoints_get($user->guid);
$users_approved_points = $users_points['approved'];
$user->userpoints_points = (int) $users_approved_points;

return elgg_ok_response('', elgg_echo('elggx_userpoints:restore:success', [$username]), REFERER);
