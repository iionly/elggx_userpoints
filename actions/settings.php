<?php
/**
 * Save Userpoints settings
 */

// Params array (text boxes and drop downs)
$params = (array) get_input('params');
foreach ($params as $k => $v) {
	if (!elgg_set_plugin_setting($k, $v, 'elggx_userpoints')) {
		return elgg_error_response(elgg_echo('plugins:settings:save:fail', [elggx_userpoints]));
	}
}

return elgg_ok_response('', elgg_echo('elggx_userpoints:settings:save:ok'), REFERER);
