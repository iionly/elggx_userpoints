<?php

$guid = (int) get_input('guid');
$status = get_input('status');

$entity = get_entity($guid);
if (!($entity instanceof Userpoint)) {
	return elgg_error_response(elgg_echo('elggx_userpoints:approved_error'));
}

elggx_userpoints_moderate($guid, $status);

return elgg_ok_response('', elgg_echo("elggx_userpoints:{$status}_message", [elgg_echo('elggx_userpoints:lowerplural')]), REFERER);
