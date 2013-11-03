<?php

$user_guid = (int)get_input('user_guid');

// Delete all the userpoint objects for the selected user
// or for all users if user_guid = 0
$entities = elgg_get_entities(array('type' => 'object', 'subtype' => 'userpoint', 'owner_guid' => $user_guid));
foreach ($entities as $entity) {
    $entity->delete();
}

if ($user_guid > 0) {
    // Remove the userpoints_points metadata for the selected user
    $options = array(
                    'guid' => $user_guid,
                    'metadata_name' => 'userpoints_points'
            );
    elgg_delete_metadata($options);
} else {
    // Remove all userpoints_points metadata
    $prefix = elgg_get_config('dbprefix');
    delete_data("DELETE from {$prefix}metadata where name_id=" . elgg_get_metastring_id('userpoints_points', true));
}

system_message(elgg_echo("elggx_userpoints:reset:success", array(elgg_echo('elggx_userpoints:lowerplural'))));
forward(REFERER);
