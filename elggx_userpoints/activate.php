<?php
/**
 * Activate Elggx Userpoints plugin
 *
 */

// Register the Userpoint class for the object/userpoint subtype
if (get_subtype_id('object', 'userpoint')) {
	update_subtype('object', 'userpoint', 'Userpoint');
} else {
	add_subtype('object', 'userpoint', 'Userpoint');
}


// Upgrade settings
$oldversion = elgg_get_plugin_setting('version', 'userpoints');
$current_version = elgg_get_plugin_setting('version', 'elggx_userpoints');

// Check if we need to run an upgrade
if ($oldversion && !$current_version) {

	// Update plugin settings
	$plugin = elgg_get_plugin_from_id('userpoints');

	elgg_set_plugin_setting('moderate', $plugin->moderate, 'elggx_userpoints');
	elgg_set_plugin_setting('subtract', $plugin->subtract, 'elggx_userpoints');
	elgg_set_plugin_setting('displaymessage', $plugin->displaymessage, 'elggx_userpoints');
	elgg_set_plugin_setting('profile_display', $plugin->profile_display, 'elggx_userpoints');
	elgg_set_plugin_setting('delete', $plugin->delete, 'elggx_userpoints');
	elgg_set_plugin_setting('expire_after', $plugin->expire_after, 'elggx_userpoints');

	// Update point settings
	$pointssettings = elgg_get_plugin_from_id('userpoints_standard');

	elgg_set_plugin_setting('blog', $pointssettings->blog, 'elggx_userpoints');
	elgg_set_plugin_setting('group', $pointssettings->group, 'elggx_userpoints');
	elgg_set_plugin_setting('profileupdate', $pointssettings->profileupdate, 'elggx_userpoints');
	elgg_set_plugin_setting('page_top', $pointssettings->page_top, 'elggx_userpoints');
	elgg_set_plugin_setting('comment', $pointssettings->generic_comment, 'elggx_userpoints');
	elgg_set_plugin_setting('riverpost', $pointssettings->riverpost, 'elggx_userpoints');
	elgg_set_plugin_setting('thewire', $pointssettings->thewire, 'elggx_userpoints');
	elgg_set_plugin_setting('image', $pointssettings->upload_photo, 'elggx_userpoints');
	elgg_set_plugin_setting('poll', $pointssettings->poll, 'elggx_userpoints');
	elgg_set_plugin_setting('pollvote', $pointssettings->pollvote, 'elggx_userpoints');
	elgg_set_plugin_setting('phototag', $pointssettings->phototag, 'elggx_userpoints');
	elgg_set_plugin_setting('discussion_reply', $pointssettings->group_topic_post, 'elggx_userpoints');
	elgg_set_plugin_setting('login', $pointssettings->login, 'elggx_userpoints');
	elgg_set_plugin_setting('delete', $pointssettings->delete, 'elggx_userpoints');
	elgg_set_plugin_setting('invite', $pointssettings->invite, 'elggx_userpoints');
	elgg_set_plugin_setting('verify_email', $pointssettings->verify_email, 'elggx_userpoints');
	elgg_set_plugin_setting('require_registration', $pointssettings->require_registration, 'elggx_userpoints');
	elgg_set_plugin_setting('expire_invite', $pointssettings->expire_invite, 'elggx_userpoints');

	// Set new version
	elgg_set_plugin_setting('version', '1.9.8', 'elggx_userpoints');
} else if ($current_version < '1.9.7') {
	$pointssettings = elgg_get_plugin_from_id('elggx_userpoints');
	elgg_set_plugin_setting('discussion_reply', $pointssettings->group_topic_post, 'elggx_userpoints');
	elgg_set_plugin_setting('comment', $pointssettings->generic_comment, 'elggx_userpoints');
	// Set new version
	elgg_set_plugin_setting('version', '1.9.8', 'elggx_userpoints');
} else if ($current_version < '1.9.8') {
	$pointssettings = elgg_get_plugin_from_id('elggx_userpoints');
	elgg_set_plugin_setting('comment', $pointssettings->generic_comment, 'elggx_userpoints');
	// Set new version
	elgg_set_plugin_setting('version', '1.9.8', 'elggx_userpoints');
}
$current_version = elgg_get_plugin_setting('version', 'elggx_userpoints');
if ($current_version != '1.9.8') {
	// Set new version
	elgg_set_plugin_setting('version', '1.9.8', 'elggx_userpoints');
}
