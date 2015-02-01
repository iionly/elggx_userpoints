<?php

$options = array('type' => 'user', 'limit' => false);
$all_users = new ElggBatch('elgg_get_entities', $options);

foreach($all_users as $user) {

    $options = array('guid' => $user->guid, 'metadata_name' => 'userpoints_points');
    elgg_delete_metadata($options);

    $users_points = userpoints_get($user->guid);
    $users_approved_points = $users_points['approved'];
    $user->userpoints_points = (int)$users_approved_points;
}

system_message(elgg_echo("elggx_userpoints:restore_all:success"));
forward(REFERER);
