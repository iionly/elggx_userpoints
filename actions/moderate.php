<?php

$guid = (int)get_input('guid');
$status = get_input('status');

$entity = get_entity($guid);
if ($entity && elgg_instanceof($entity, 'object', 'userpoint')) {
	userpoints_moderate($guid, $status);

	system_message(elgg_echo("elggx_userpoints:".$status."_message", array(elgg_echo('elggx_userpoints:lowerplural'))));
} else {
	register_error(elgg_echo("elggx_userpoints:approved_error"));
}

forward(REFERER);
