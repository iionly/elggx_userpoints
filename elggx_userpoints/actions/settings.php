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

$comment_points = elgg_get_plugin_setting('generic_comment', 'elggx_userpoints');
if ($comment_points && $comment_points > 0) {
    elgg_set_plugin_setting('comment', $comment_points, 'elggx_userpoints');
} else {
    elgg_unset_plugin_setting('comment', 'elggx_userpoints');
}

system_message(elgg_echo('elggx_userpoints:settings:save:ok'));
forward(REFERER);
