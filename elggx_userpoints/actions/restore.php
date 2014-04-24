<?php

$username = get_input('username');
$user = get_user_by_username($username);

if($user) {

    $options = array('guid' => $user->guid, 'metadata_name' => 'userpoints_points');
    elgg_delete_metadata($options);

    $users_points = userpoints_get($user->guid);
    $users_approved_points = $users_points['approved'];
    $user->userpoints_points = (int)$users_approved_points;

    system_message(elgg_echo("elggx_userpoints:restore:success", array($user->username)));
    forward(REFERER);

} else {
    register_error(elgg_echo("elggx_userpoints:restore:error", array($username)));
    forward(REFERER);
}
