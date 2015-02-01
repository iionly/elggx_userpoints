<?php

elgg_register_event_handler('init','system','userpoints_init');

function userpoints_init() {

	// Register library
	elgg_register_library('userpoints_library', elgg_get_plugins_path() . 'elggx_userpoints/lib/userpoint.php');
	elgg_load_library('userpoints_library');

	elgg_register_plugin_hook_handler('expirationdate:expire_entity', 'all', 'elggx_userpoints_expire');

	elgg_extend_view('css/elgg', 'elggx_userpoints/css');
	elgg_extend_view('icon/user/default','elggx_userpoints/icon');

	elgg_register_widget_type('toppoints', elgg_echo('elggx_userpoints:toppoints'), elgg_echo('elggx_userpoints:widget:toppoints:info'));
	elgg_register_widget_type('index_toppoints', elgg_echo('elggx_userpoints:toppoints'), elgg_echo('elggx_userpoints:toppoints'), array('index'));

	// Hooks for awarding points
	elgg_register_plugin_hook_handler('action', 'invitefriends/invite', 'elggx_userpoints_invite');
	elgg_register_plugin_hook_handler('action', 'register', 'elggx_userpoints_register');
	elgg_register_plugin_hook_handler('action', 'recommendations/new', 'elggx_userpoints_recommendations');
	elgg_register_plugin_hook_handler('action', 'recommendations/approve', 'elggx_userpoints_recommendations');
	elgg_register_plugin_hook_handler('action', 'friends/add', 'elggx_userpoints_friend');
	elgg_register_event_handler('enable', 'user', 'elggx_userpoints_validate');
	elgg_register_event_handler('login:after','user', 'elggx_userpoints_login');
	elgg_register_event_handler('create','object', 'elggx_userpoints_object');
	elgg_register_event_handler('delete','object', 'elggx_userpoints_object');
	elgg_register_event_handler('delete','entity', 'elggx_userpoints_object');
	elgg_register_event_handler('create','annotation','elggx_userpoints_annotate_create');
	elgg_register_event_handler('create','group','elggx_userpoints_group');
	elgg_register_event_handler('delete','group','elggx_userpoints_group');
	elgg_register_event_handler('profileupdate','user','elggx_userpoints_profile');
	elgg_register_event_handler('profileiconupdate','user','elggx_userpoints_profileiconupdate');

	elgg_register_admin_menu_item('administer', 'elggx_userpoints', 'administer_utilities');

	// Register actions
	$base_dir = elgg_get_plugins_path() . 'elggx_userpoints/actions';
	elgg_register_action("elggx_userpoints/settings", "$base_dir/settings.php", 'admin');
	elgg_register_action("elggx_userpoints/delete", "$base_dir/delete.php", 'admin');
	elgg_register_action("elggx_userpoints/moderate", "$base_dir/moderate.php", 'admin');
	elgg_register_action("elggx_userpoints/add", "$base_dir/add.php", 'admin');
	elgg_register_action("elggx_userpoints/reset", "$base_dir/reset.php", 'admin');
	elgg_register_action("elggx_userpoints/restore", "$base_dir/restore.php", 'admin');
	elgg_register_action("elggx_userpoints/restore_all", "$base_dir/restore_all.php", 'admin');
}
