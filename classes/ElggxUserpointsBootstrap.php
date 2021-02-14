<?php

use Elgg\DefaultPluginBootstrap;

class ElggxUserpointsBootstrap extends DefaultPluginBootstrap {

	public function init() {
		// Entity Menu
		elgg_register_plugin_hook_handler('register', 'menu:entity', 'elggx_userpoints_entity_menu');

		// Add support for expirationdate
		elgg_register_plugin_hook_handler('expirationdate:expire_entity', 'all', 'elggx_userpoints_expire');

		// Hooks for awarding points
		elgg_register_plugin_hook_handler('action', 'invitefriends/invite', 'elggx_userpoints_invite');
		elgg_register_plugin_hook_handler('action:validate', 'register', 'elggx_userpoints_register');

		elgg_register_plugin_hook_handler('register', 'menu:filter:members', 'elggx_userpoints_members_nav');

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
	}

	public function activate() {
		$current_version = elgg_get_plugin_setting('version', 'elggx_userpoints');
		$new_version = '3.3.0';

		if (version_compare($current_version, '3.0.0', '<')) {
			// Elgg 3 saves discussion replies as comment entities so no longer a separate points setting possible
			elgg_unset_plugin_setting('recommendations_approve', 'elggx_userpoints');

			// Set new version
			elgg_set_plugin_setting('version', $new_version, 'elggx_userpoints');
		} else if (version_compare($current_version, $new_version, '!=')) {
			// Set new version
			elgg_set_plugin_setting('version', $new_version, 'elggx_userpoints');
		}
	}
}
