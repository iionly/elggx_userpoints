<?php
/**
 * Save Userpoints settings
 */

$params = (array) get_input('params');
$plugin = elgg_get_plugin_from_id('elggx_userpoints');
if (!$plugin) {
	return elgg_error_response(elgg_echo('plugins:settings:save:fail', [$plugin_id]));
}
$plugin_name = $plugin->getDisplayName();

$result = false;

foreach ($params as $k => $v) {
	$result = $plugin->setSetting($k, $v);
	if (!$result) {
		return elgg_error_response(elgg_echo('plugins:settings:save:fail', [$plugin_name]));
	}
}

return elgg_ok_response('', elgg_echo('elggx_userpoints:settings:save:ok'), REFERER);