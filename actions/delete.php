<?php

$guid = (int) get_input('guid');

$entity = get_entity($guid);
if (!($entity instanceof Userpoint)) {
	return elgg_error_response(elgg_echo('elggx_userpoints:delete_error'));
}

elggx_userpoints_delete_by_userpoint($guid);

return elgg_ok_response('', elgg_echo('elggx_userpoints:delete_success'), REFERER);
