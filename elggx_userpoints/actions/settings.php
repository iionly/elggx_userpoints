<?php

/**
 * Save Userpoints settings
 *
 */

// Params array (text boxes and drop downs)
$params = get_input('params');
$result = false;
foreach ($params as $k => $v) {
    if (!elgg_set_plugin_setting($k, $v, 'elggx_userpoints')) {
        register_error(elgg_echo('plugins:settings:save:fail', array('elggx_userpoints')));
        forward(REFERER);
    }
}

system_message(elgg_echo('elggx_userpoints:settings:save:ok'));
forward(REFERER);
