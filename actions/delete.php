<?php

$guid = (int)get_input('guid');

$entity = get_entity($guid);
if ($entity && elgg_instanceof($entity, 'object', 'userpoint')) {
	userpoints_delete_by_userpoint($guid);

	system_message(elgg_echo("elggx_userpoints:delete_success"));
} else {
	register_error(elgg_echo("elggx_userpoints:delete_error"));
}
forward(REFERER);
