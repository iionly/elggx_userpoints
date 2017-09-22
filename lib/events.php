<?php

function elggx_userpoints_validate($event, $object_type, $object) {

	if ($event === 'enable' && $object_type ==='user' && $object instanceof ElggUser) {
		elggx_userpoints_registration_award($object->email);
	}
}

function elggx_userpoints_login($event, $type, $user) {
	
	$points = (int) elgg_get_plugin_setting('login', 'elggx_userpoints');
	if (empty($points)) {
		// no point configured for login
		return;
	}
	
	// Check to see if the configured amount of time
	// has passed before awarding more login points
	$diff = time() - (int) $user->userpoints_login;
	if ($diff < (int) elgg_get_plugin_setting('login_threshold', 'elggx_userpoints')) {
		return;
	}
	
	// Check to see if the user has logged in frequently enough
	$interval = (int) elgg_get_plugin_setting('login_interval', 'elggx_userpoints') * 86400;
	$time_since_last_login = time() - $user->prev_last_login;

	if (($time_since_last_login < $interval) || !$user->prev_last_login) {
		
		// The login threshold has been met so now add the points
		if (!elggx_userpoints_add($user->guid, $points, 'Login')) {
			return;
		}
		
		$user->userpoints_login = time();
	}
}

function elggx_userpoints_object($event, $object_type, $object) {
	
	if ($event == 'create') {
		$subtype = $object->getSubtype();
		if ($points = elgg_get_plugin_setting($subtype, 'elggx_userpoints')) {
			elggx_userpoints_add($object->owner_guid, $points, $subtype, $subtype, $object->guid);
		}
	} else if ($event == 'delete') {
		$subtype = $object->getSubtype();
		if ($points = elgg_get_plugin_setting($subtype, 'elggx_userpoints')) {
			elggx_userpoints_delete($object->owner_guid, $object->guid);
		}
	}
}

function elggx_userpoints_annotate_create($event, $object_type, $object) {
	
	if ($points = elgg_get_plugin_setting($object->name, 'elggx_userpoints')) {
		$description = $object->name;
		elggx_userpoints_add($object->owner_guid, $points, $description, $object_type, $object->entity_guid);
	}
}

function elggx_userpoints_group($event, $object_type, $object) {
	
	if ($event == 'create') {
		if ($points = elgg_get_plugin_setting($object_type, 'elggx_userpoints')) {
			elggx_userpoints_add(elgg_get_logged_in_user_guid(), $points, $object_type, $object_type, $object->guid);
		}
	} else if ($event == 'delete') {
		elggx_userpoints_delete(elgg_get_logged_in_user_guid(), $object->guid);
	}
}

function elggx_userpoints_profile($event, $type, $object) {
	
	if ($points = elgg_get_plugin_setting('profileupdate', 'elggx_userpoints')) {
		elggx_userpoints_add(elgg_get_logged_in_user_guid(), $points, $event, $type, $object->entity_guid);
	}
}

function elggx_userpoints_profileiconupdate($event, $type, $object) {
	
	if ($points = elgg_get_plugin_setting('profileicon', 'elggx_userpoints')) {
		elggx_userpoints_add(elgg_get_logged_in_user_guid(), $points, $event, $type, $object->entity_guid);
	}
}
