<?php

/**
 * Add pending points to a user
 *
 * This method is intended to be called by other plugins
 * that need to add points pending some future action.
 *
 * An example would be inviting friends but the points are
 * awarded pending registration. The plugin calling this
 * method is responsible for calling userpoints_moderate()
 * when the points should be awarded.
 *
 * @param integer  $guid User Guid
 * @param integer  $points The number of ppoints to add
 * @param string   $description Description for these points
 * @param string   $type The entity type that the points are being awarded for
 * @param integer  $guid The entity guid
 * @return object  The userpoint object
 */
function userpoints_add_pending($user_guid, $points, $description, $type=null, $guid=null) {

	$points = (int)$points;

	// Create and save our new Userpoint object
	$userpoint = new Userpoint(null, $user_guid, $description);
	$userpoint->save();

	// Add the points, type, and guid as metadata to the user object
	$userpoint->meta_points = $points;
	$userpoint->meta_type = $type;
	$userpoint->meta_guid = $guid;
	$userpoint->meta_moderate = 'pending';

	return($userpoint);
}

/**
 * Add points to a user
 *
 * @param integer  $guid User Guid
 * @param integer  $points The number of ppoints to add
 * @param string   $description Description for these points
 * @param string   $type The entity type that the points are being awarded for
 * @param integer  $guid The entity guid
 * @return Bool    Return true/false on success/failure
 */
function userpoints_add($user_guid, $points, $description, $type=null, $guid=null) {

	$points = (int)$points;

	// Create and save our new Userpoint object
	$userpoint = new Userpoint(null, $user_guid, $description);
	$userpoint->save();

	// Just in case the save fails
	if (!$userpoint->guid) {
		return(false);
	}

	// Add the points, type, and guid as metadata to the user object
	$userpoint->meta_points = $points;
	$userpoint->meta_type = $type;
	$userpoint->meta_guid = $guid;

	if (!elgg_trigger_plugin_hook('userpoints:add', $userpoint->type, array('entity' => $userpoint), true)) {
		$userpoint->delete();
		return(false);
	}

	// If moderation is enabled set points to pending else they are auto approved
	if (elgg_get_plugin_setting('moderate', 'elggx_userpoints') && $type != 'admin') {
		$userpoint->meta_moderate = 'pending';
	} else {
		$userpoint->meta_moderate = 'approved';
		userpoints_update_user($user_guid, $points);
	}

	// Setup point expiration if enabled
	if (elgg_get_plugin_setting('expire_after', 'elggx_userpoints')) {
		if (function_exists('expirationdate_set')) {
			$ts = time() + elgg_get_plugin_setting('expire_after', 'elggx_userpoints');
			expirationdate_set($userpoint->guid, date('Y-m-d H:i:s', $ts), false);
		}
	}

	// Display a system message to the user if configured to do so
	$branding = ($points == 1) ? elgg_echo('elggx_userpoints:lowersingular') : elgg_echo('elggx_userpoints:lowerplural');
	if (elgg_get_plugin_setting('displaymessage', 'elggx_userpoints') && $type != 'admin' && $user_guid == elgg_get_logged_in_user_guid()) {
		$message = elgg_get_plugin_setting('moderate', 'elggx_userpoints') ? 'elggx_userpoints:pending_message' : 'elggx_userpoints:awarded_message';
		system_message(elgg_echo($message, array($points, $branding)));
	}

	return($userpoint);
}

/**
 * Subtract points from a user. This is just a wrapper around
 * userpoints_add as we are really just adding negataive x points.
 *
 * @param integer  $guid User Guid
 * @param integer  $points The number of points to subtract
 * @param string   $description Description for these points
 * @param string   $type The entity type that the points are being awarded for
 * @param integer  $guid The entity guid
 * @return Bool    Return true/false on success/failure
 */
function userpoints_subtract($user_guid, $points, $description, $type=null, $guid=null) {
	if ($points > 0) {
		$points = -$points;
	}

	return(userpoints_add($user_guid, $points, $description, $type=null, $guid=null));
}

/**
 * Called when the expirationdate:expire_entity hook is triggered.
 * When a userpoint record is expired we have to decrement the users
 * total points.
 *
 * @param integer  $hook The hook being called.
 * @param integer  $type The type of entity you're being called on.
 * @param string   $return The return value.
 * @param string   $params An array of parameters including the userpoint entity
 * @return Bool    Return true
 */
function elggx_userpoints_expire($hook, $type, $return, $params) {

	if (!$params['entity']->getSubtype() == 'userpoint') {
		return(true);
	}

	$user = get_user($params['entity']->owner_guid);

	// Decrement the users total points
	userpoints_update_user($params['entity']->owner_guid, -$params['entity']->meta_points);

	return(true);
}

/**
 * Given a user id, type, and entity id check to see if points have
 * already been awarded.
 *
 * @param  integer  $user_guid User Guid
 * @param  string   $type The entity type that the points are being awarded for
 * @param  integer  $guid The entity guid
 * @return Bool
 */
function userpoints_exists($user_guid, $type, $guid) {
	$entities = elgg_get_entities_from_metadata(array(
					'metadata_name' => 'meta_type',
					'type' => 'object',
					'subtype' => 'userpoint',
					'owner_guid' => $user_guid,
					'limit' => false
				));

	foreach($entities as $obj) {
		if ($obj->meta_type == $type && $obj->meta_guid == $guid) {
			return(true);
		}
	}
	return(false);
}

/**
 * Returns a count of approved and pending points for the given user.
 *
 * @param  integer  $user_guid The user Guid
 * @return array    An array including the count of approved/pending points
 */
function userpoints_get($user_guid) {

	$points = array('approved' => 0, 'pending' => 0);

	if ($entities = elgg_get_entities_from_metadata(array('metadata_name' => 'meta_points', 'type' => 'object', 'subtype' => 'userpoint', 'owner_guid' => $user_guid, 'limit' => false))) {
		foreach($entities as $obj) {
			if (isset($obj->meta_moderate)) {
				if ($obj->meta_moderate == 'approved') {
					$points['approved'] = $points['approved'] + $obj->meta_points;
				} else if ($obj->meta_moderate == 'pending') {
					$points['pending'] = $points['pending'] + $obj->meta_points;
				}
			} else {
				$points['approved'] = $points['approved'] + $obj->meta_points;
			}
		}
	}
	return($points);
}

/**
 * Deletes a userpoint record based on the meta_guid. This method
 * should be called by plugins that want to delete points if the
 * content/object that awarded the points is deleted.
 *
 * @param  integer  $user_guid The user Guid
 * @param  integer  $guid The guid of the object being deleted
 */
function userpoints_delete($user_guid, $guid) {

	if (!elgg_get_plugin_setting('delete', 'elggx_userpoints')) {
		return(false);
	}

	$points = 0;

	$entities = elgg_get_entities_from_metadata(array('metadata_name' => 'meta_guid', 'metadata_value' => $guid, 'type' => 'object', 'subtype' => 'userpoint', 'owner_guid' => $user_guid, 'limit' => false));
	foreach ($entities as $entity) {
		$points = $points + $entity->meta_points;
		$entity->delete();
	}

	$user = get_user($user_guid);

	// Decrement the users total points
	userpoints_update_user($user_guid, -$points);
}

/**
 * Deletes userpoints by the guid of the userpoint entity.
 * This method is called when administratively deleting points
 * or when points expire.
 *
 * @param  integer  $guid The guid of the userpoint entity
 */
function userpoints_delete_by_userpoint($guid) {

	$entity = get_entity($guid);
	$owner_guid = $entity->owner_guid;
	$points = $entity->meta_points;

	// Delete the userpoint entity
	$entity->delete();

	// Decrement the users total points
	userpoints_update_user($owner_guid, -$points);
}

/**
 * Update the users running points total
 *
 * @param  integer  $guid   The guid of the userpoint entity
 * @param  integer  $points The number of points to be added (or subtracted if a negative value)
 */
function userpoints_update_user($guid, $points) {
	$user = get_user($guid);

	if(is_int($user->userpoints_points)) {
		$user->userpoints_points = (int)$user->userpoints_points + (int)$points;
	} else {
		$options = array('guid' => $user->guid, 'metadata_name' => 'userpoints_points');
		elgg_delete_metadata($options);

		$users_points = userpoints_get($user->guid);
		$users_approved_points = $users_points['approved'];
		$user->userpoints_points = (int)$users_approved_points;
	}

	if (!elgg_trigger_plugin_hook('userpoints:update', 'object', array('entity' => $user), true)) {
		$user->userpoints_points = (int)$user->userpoints_points - (int)$points;
		return(false);
	}
}

/**
 * Deletes userpoints by the guid of the userpoint entity.
 * This method is called when administratively deleting points
 * or when points expire.
 *
 * @param  integer  $guid The guid of the userpoint entity
 */
function userpoints_moderate($guid, $status) {

	$entity = get_entity($guid);

	$entity->meta_moderate = $status;

	// increment the users total points if approved
	if ($status == 'approved') {
		userpoints_update_user($entity->owner_guid, $entity->meta_points);
	}
}


// In the following are functions for adding points for various actions

function elggx_userpoints_object($event, $object_type, $object) {
	if (function_exists('userpoints_add')) {
		if ($event == 'create') {
			$subtype = $object->getSubtype();
			if ($points = elgg_get_plugin_setting($subtype, 'elggx_userpoints')) {
				userpoints_add(elgg_get_logged_in_user_guid(), $points, $subtype, $subtype, $object->guid);
			}
		} else if ($event == 'delete') {
			userpoints_delete(elgg_get_logged_in_user_guid(), $object->guid);
		}
	}

	return(true);
}

function elggx_userpoints_annotate_create($event, $object_type, $object) {
	if ($points = elgg_get_plugin_setting($object->name, 'elggx_userpoints')) {
		if (function_exists('userpoints_add')) {
			$description = $object->name;
			userpoints_add(elgg_get_logged_in_user_guid(), $points, $description, $object_type, $object->entity_guid);
		}
	}

	return(true);
}

function elggx_userpoints_friend($hook, $action) {

	if (function_exists('userpoints_add')) {
		if ($action == 'friends/add') {
			$user = get_user(get_input('friend'));
			if ($points = elgg_get_plugin_setting('friend', 'elggx_userpoints')) {
				userpoints_add(elgg_get_logged_in_user_guid(), $points, 'Making '.$user->name.' a friend');
				return(true);
			}
		}
	}
}

function elggx_userpoints_recommendations($hook, $action) {

	$approval = (int) elgg_get_plugin_setting('recommendations_approve', 'elggx_userpoints');
	$points = (int) elgg_get_plugin_setting('recommendation', 'elggx_userpoints');

	if ($action == 'recommendations/new' && !$approval) {
		$user = get_user(get_input('recommendation_to'));
		userpoints_add(elgg_get_logged_in_user_guid(), $points, 'Recommending '.$user->name, 'recommendation');
		return(true);
	}

	if ($action == 'recommendations/approve') {

		$entity_guid = (int) get_input('entity_guid');
		$entity = get_entity($entity_guid);
		$user = get_user($entity->recommendation_to);

		$description = '<a href='.$entity->getUrl().'>'.$entity->title.'</a>';

		$access = elgg_set_ignore_access(true);

		userpoints_add($entity->owner_guid, $points, $description, 'recommendation');

		elgg_set_ignore_access($access);

		return(true);
	}
}

function elggx_userpoints_profile($event, $type, $object) {
	if ($points = elgg_get_plugin_setting('profileupdate', 'elggx_userpoints')) {
		if (function_exists('userpoints_add')) {
			userpoints_add(elgg_get_logged_in_user_guid(), $points, $event, $type, $object->entity_guid);
		}
	}

	return(true);
}

function elggx_userpoints_profileiconupdate($event, $type, $object) {
	if ($points = elgg_get_plugin_setting('profileicon', 'elggx_userpoints')) {
		if (function_exists('userpoints_add')) {
			userpoints_add(elgg_get_logged_in_user_guid(), $points, $event, $type, $object->entity_guid);
		}
	}

	return(true);
}

function elggx_userpoints_group($event, $object_type, $object) {
	if (function_exists('userpoints_add')) {
		if ($event == 'create') {
			if ($points = elgg_get_plugin_setting($object_type, 'elggx_userpoints')) {
				userpoints_add(elgg_get_logged_in_user_guid(), $points, $object_type, $object_type, $object->guid);
			}
		} else if ($event == 'delete') {
			userpoints_delete(elgg_get_logged_in_user_guid(), $object->guid);
		}
	}

	return(true);
}

function elggx_userpoints_login($event, $type, $user) {
	// Check to see if the configured amount of time
	// has passed before awarding more login points
	$diff = time() - $user->userpoints_login;

	if ($diff > elgg_get_plugin_setting('login_threshold', 'elggx_userpoints')) {

		// Check to see if the user has logged in frequently enough
		$s = (int) elgg_get_plugin_setting('login_interval', 'elggx_userpoints') * 86400;
		$diff = time() - $user->prev_last_login;

		if (($diff < $s) || !$user->prev_last_login) {

			// The login threshold has been met so now add the points

			$user_guid = $user->getGUID();
			$points = elgg_get_plugin_setting('login', 'elggx_userpoints');

			// Create and save our new Userpoint object
			$userpoint = new Userpoint(null, $user_guid, 'Login');
			$userpoint->save();

			// Add the points, type, and guid as metadata to the user object
			$userpoint->meta_points = $points;
			$userpoint->meta_type = null;
			$userpoint->meta_guid = null;

			if (!elgg_trigger_plugin_hook('userpoints:add', $userpoint->type, array('entity' => $userpoint), true)) {
				$userpoint->delete();
			}

			// If moderation is enabled set points to pending else they are auto approved
			if (elgg_get_plugin_setting('moderate', 'elggx_userpoints')) {
				$userpoint->meta_moderate = 'pending';
			} else {
				$userpoint->meta_moderate = 'approved';
				userpoints_update_user($user_guid, $points);
			}

			// Setup point expiration if enabled
			if (elgg_get_plugin_setting('expire_after', 'elggx_userpoints')) {
				if (function_exists('expirationdate_set')) {
					$ts = time() + elgg_get_plugin_setting('expire_after', 'elggx_userpoints');
					expirationdate_set($userpoint->guid, date('Y-m-d H:i:s', $ts), false);
				}
			}

			// Display a system message to the user if configured to do so
			$branding = ($points == 1) ? elgg_echo('elggx_userpoints:lowersingular') : elgg_echo('elggx_userpoints:lowerplural');
			if (elgg_get_plugin_setting('displaymessage', 'elggx_userpoints')) {
				$message = elgg_get_plugin_setting('moderate', 'elggx_userpoints') ? 'elggx_userpoints:pending_message' : 'elggx_userpoints:awarded_message';
				system_message(elgg_echo($message, array($points, $branding)));
			}

			$user->userpoints_login = time();
		}
	}

	return(true);
}

/**
 * Hooks on the enable user Entity event and checks to see if the inviting
 * user has a pending userpoints record for the invited user.
 */
function elggx_userpoints_validate($event, $object_type, $object) {

	if($event == 'enable' && $object_type=='user' && $object instanceof ElggUser) {
		elggx_userpoints_registration_award($object->email);
	}
}

/**
 * Hooks on the register action and checks to see if the inviting
 * user has a pending userpoints record for the invited user. If
 * the uservalidationbyemail plugin is enabled then points will
 * not be awarded until the invited user verifies their email
 * address.
 */
function elggx_userpoints_register() {

	$email = get_input('email');

	if (elgg_is_active_plugin('uservalidationbyemail')) {
		return(true);
	}

	// No email validation configured so award the points
	elggx_userpoints_registration_award($email);

	return(true);
}

/**
 * Hooks on the invitefriends/invite action and either awards
 * points for the invite or sets up a pending userpoint record
 * where points can be awarded when the invited user registers.
 */
function elggx_userpoints_invite() {

	if (!$points = elgg_get_plugin_setting('invite', 'elggx_userpoints')) {
		return;
	}

	$emails = get_input('emails');
	$emails = explode("\n",$emails);

	if (sizeof($emails)) {
		foreach($emails as $email) {

			$email = trim($email);

			if (get_user_by_email($email)) {
				continue;
			}

			if (elgg_get_plugin_setting('verify_email', 'elggx_userpoints') && !elggx_userpoints_validEmail($email)) {
				continue;
			}

			if ((int)elgg_get_plugin_setting('require_registration', 'elggx_userpoints')) {
				if (!elggx_userpoints_invite_status(elgg_get_logged_in_user_guid(), $email)) {
					$userpoint = userpoints_add_pending(elgg_get_logged_in_user_guid(), $points, $email, 'invite');
					if (elgg_is_active_plugin('expirationdate') && $expire = (int)elgg_get_plugin_setting('expire_invite', 'elggx_userpoints')) {
						$ts = time() + $expire;
						expirationdate_set($userpoint->guid, date('Y-m-d H:i:s', $ts), false);
					}
				}
			} else {
				if (!elggx_userpoints_invite_status(elgg_get_logged_in_user_guid(), $email)) {
					userpoints_add(elgg_get_logged_in_user_guid(), $points, $email, 'invite');
					$userpoint = userpoints_add_pending(elgg_get_logged_in_user_guid(), 0, $email, 'invite');
					if (elgg_is_active_plugin('expirationdate') && $expire = (int)elgg_get_plugin_setting('expire_invite', 'elggx_userpoints')) {
						$ts = time() + $expire;
						expirationdate_set($userpoint->guid, date('Y-m-d H:i:s', $ts), false);
					}
				}
			}
		}
	}
}

/**
 * Check for an existing pending invite for the given email address.
 *
 * @param string   $email The amail address of the invited user
 * @return Bool    Return true/false on pending record found or not
 */
function elggx_userpoints_registration_award($email) {

	$access = elgg_set_ignore_access(true);

	$guids = elggx_userpoints_invite_status(null, $email);

	if (!empty($guids)) {
		foreach ($guids as $guid) {
			$entity = get_entity($guid);
			$entity->meta_moderate = 'approved';

			$user = get_user($entity->owner_guid);

			if(is_int($user->userpoints_points)) {
				$user->userpoints_points = (int)$user->userpoints_points + (int)$entity->meta_points;
			} else {
				$options = array('guid' => $user->guid, 'metadata_name' => 'userpoints_points');
				elgg_delete_metadata($options);

				$users_points = userpoints_get($user->guid);
				$users_approved_points = $users_points['approved'];
				$user->userpoints_points = (int)$users_approved_points;
			}

			if (!elgg_trigger_plugin_hook('userpoints:update', 'object', array('entity' => $user), true)) {
				$user->userpoints_points = (int)$user->userpoints_points - (int)$entity->meta_points;
			}
		}
	}

	elgg_set_ignore_access($access);

	return;
}


/**
 * Check for an existing pending invite for the given email address.
 *
 * @param integer  $guid The inviting users guid
 * @param string   $email The amail address of the invited user
 * @return mixed   Return userpoint guid on pending otherwise return moderation status or false if no record
 */
function elggx_userpoints_invite_status($guid = null, $email) {

	$status = false;

	$access = elgg_set_ignore_access(true);

	$options = array(
			'type' => 'object',
			'subtype' => 'userpoint',
			'owner_guid' => $guid,
			'limit' => false
	);

	$options['metadata_name_value_pairs'] = array(
					array('name' => 'meta_type', 'value' => 'invite',  'operand' => '='),
					array('name' => 'meta_moderate', 'value' => 'pending',  'operand' => '=')
			);

	$entities = elgg_get_entities_from_metadata($options);

	foreach ($entities as $entity) {
		if ($entity->description == $email) {
			$status[] = $entity->guid;
		}
	}

	elgg_set_ignore_access($access);

	return($status);
}

/**
 * Validate an email address
 * Source: http://www.linuxjournal.com/article/9585
 *
 * Returns true if the email has the proper email address
 * has the proper format and the domain exists.
 *
 * @param string   $email The amail address to verify
 * @return Bool    Return true/false on success/failure
 */
function elggx_userpoints_validEmail($email) {
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if (is_bool($atIndex) && !$atIndex) {
		$isValid = false;
	} else {
		$domain = substr($email, $atIndex+1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64) {
			// local part length exceeded
			$isValid = false;
		} else if ($domainLen < 1 || $domainLen > 255) {
			// domain part length exceeded
			$isValid = false;
		} else if ($local[0] == '.' || $local[$localLen-1] == '.') {
			// local part starts or ends with '.'
			$isValid = false;
		} else if (preg_match('/\\.\\./', $local)) {
			// local part has two consecutive dots
			$isValid = false;
		} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
			// character not valid in domain part
			$isValid = false;
		} else if (preg_match('/\\.\\./', $domain)) {
			// domain part has two consecutive dots
			$isValid = false;
		} else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
			// character not valid in local part unless
			// local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
				$isValid = false;
			}
		}
		if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
			// domain not found in DNS
			$isValid = false;
		}
	}
	return $isValid;
}
