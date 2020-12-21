<?php

$params = (array) get_input('params');

$username = elgg_extract('username', $params);
$user = get_user_by_username($username);

if (!($user instanceof ElggUser)) {
	return elgg_error_response(elgg_echo('elggx_userpoints:error:invalid_username', [$username]));
}

elggx_userpoints_add($user->guid, $params['points'], $params['description'], 'admin');

return elgg_ok_response('', elgg_echo('elggx_userpoints:add:success', [
	$params['points'],
	elgg_echo('elggx_userpoints:lowerplural'),
	$user->getDisplayName(),
]), REFERER);
