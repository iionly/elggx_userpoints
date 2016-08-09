<?php

$params = get_input('params');
$username = get_input($params['username']);
$user = get_user_by_username($username);

if ($user) {
	userpoints_add($user->guid, $params['points'], $params['description'], 'admin')
	system_message(elgg_echo("elggx_userpoints:add:success", array($params['points'], elgg_echo('elggx_userpoints:lowerplural'), $params['username'])));

} else {
	register_error(elgg_echo("elggx_userpoints:error:invalid_username", array($username)));
}
forward(REFERER);
