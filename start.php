<?php
/**
 * Main plugin file
 */

require_once(dirname(__FILE__) . '/lib/userpoint.php');
require_once(dirname(__FILE__) . '/lib/events.php');
require_once(dirname(__FILE__) . '/lib/hooks.php');

// register default Elgg event
elgg_register_event_handler('init', 'system', 'elggx_userpoints_init');

/**
 * Called during system init
 *
 * @return void
 */
function elggx_userpoints_init() {

	// Add support for expirationdate
	elgg_register_plugin_hook_handler('expirationdate:expire_entity', 'all', 'elggx_userpoints_expire');

	// Extend CSS/js
	elgg_extend_view('elgg.css', 'elggx_userpoints/site.css');
	elgg_extend_view('admin.css', 'elggx_userpoints/admin.css');
	elgg_extend_view('icon/user/default','elggx_userpoints/icon');

	// Register widgets
	elgg_register_widget_type('toppoints', elgg_echo('elggx_userpoints:toppoints'), elgg_echo('elggx_userpoints:widget:toppoints:info'));
	elgg_register_widget_type('index_toppoints', elgg_echo('elggx_userpoints:toppoints'), elgg_echo('elggx_userpoints:toppoints'), ['index']);

	// Plugin hooks
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'elggx_userpoints_entity_menu');

	// Hooks for awarding points
	elgg_register_plugin_hook_handler('action', 'invitefriends/invite', 'elggx_userpoints_invite');
	elgg_register_plugin_hook_handler('action', 'register', 'elggx_userpoints_register');

	elgg_register_plugin_hook_handler('members:list', 'elggx_userpoints', 'elggx_userpoints_members_list');
	elgg_register_plugin_hook_handler('members:config', 'tabs', 'elggx_userpoints_members_nav');

	elgg_register_event_handler('enable', 'user', 'elggx_userpoints_validate');
	elgg_register_event_handler('login:after', 'user', 'elggx_userpoints_login');
	elgg_register_event_handler('create', 'object', 'elggx_userpoints_object');
	elgg_register_event_handler('delete', 'object', 'elggx_userpoints_object');
	elgg_register_event_handler('delete', 'entity', 'elggx_userpoints_object');
	elgg_register_event_handler('create', 'annotation', 'elggx_userpoints_annotate');
	elgg_register_event_handler('delete', 'annotation', 'elggx_userpoints_annotate');
	elgg_register_event_handler('create', 'relationship', 'elggx_userpoints_relationship');
	elgg_register_event_handler('delete', 'relationship', 'elggx_userpoints_relationship');
	elgg_register_event_handler('create', 'group', 'elggx_userpoints_group');
	elgg_register_event_handler('delete', 'group', 'elggx_userpoints_group');
	elgg_register_event_handler('profileupdate', 'user', 'elggx_userpoints_profile');
	elgg_register_event_handler('profileiconupdate', 'user', 'elggx_userpoints_profileiconupdate');

	// Extend form with points settings
	elgg_extend_view('forms/elggx_userpoints/actions', 'elggx_userpoints/actions/general', 100);
	elgg_extend_view('forms/elggx_userpoints/actions', 'elggx_userpoints/actions/profile');
	elgg_extend_view('forms/elggx_userpoints/actions', 'elggx_userpoints/actions/poll');
	elgg_extend_view('forms/elggx_userpoints/actions', 'elggx_userpoints/actions/izap_videos');
	elgg_extend_view('forms/elggx_userpoints/actions', 'elggx_userpoints/actions/tidypics');
	elgg_extend_view('forms/elggx_userpoints/actions', 'elggx_userpoints/actions/elggx_fivestar');
	elgg_extend_view('forms/elggx_userpoints/actions', 'elggx_userpoints/actions/login');
	elgg_extend_view('forms/elggx_userpoints/actions', 'elggx_userpoints/actions/invite_friends');

	// Register actions
	elgg_register_action('elggx_userpoints/settings', dirname(__FILE__) . '/actions/settings.php', 'admin');
	elgg_register_action('elggx_userpoints/delete', dirname(__FILE__) . '/actions/delete.php', 'admin');
	elgg_register_action('elggx_userpoints/moderate', dirname(__FILE__) . '/actions/moderate.php', 'admin');
	elgg_register_action('elggx_userpoints/add', dirname(__FILE__) . '/actions/add.php', 'admin');
	elgg_register_action('elggx_userpoints/reset', dirname(__FILE__) . '/actions/reset.php', 'admin');
	elgg_register_action('elggx_userpoints/restore', dirname(__FILE__) . '/actions/restore.php', 'admin');
	elgg_register_action('elggx_userpoints/restore_all', dirname(__FILE__) . '/actions/restore_all.php', 'admin');
}
