<?php

/**
 * Add pending points to a user
 *
 * This method is intended to be called by other plugins
 * that need to add points pending some future action.
 *
 * An example would be inviting friends but the points are
 * awarded pending registration. The plugin calling this
 * method is responsible for calling elggx_userpoints_moderate()
 * when the points should be awarded.
 *
 * @param integer  $guid User Guid
 * @param integer  $points The number of points to add
 * @param string   $description Description for these points
 * @param string   $type The entity type that the points are being awarded for
 * @param integer  $guid The entity guid
 *
 * @return Userpoint The userpoint object
 */
function elggx_userpoints_add_pending($user_guid, $points, $description, $type = null, $guid = null) {

	$points = (int) $points;

	// Create and save our new Userpoint object
	$userpoint = new Userpoint();
	$userpoint->owner_guid = $user_guid;
	$userpoint->container_guid = $user_guid;
	$userpoint->description = $description;
	$userpoint->save();

	// Add the points, type, and guid as metadata to the user object
	$userpoint->meta_points = $points;
	$userpoint->meta_type = $type;
	$userpoint->meta_guid = $guid;
	$userpoint->meta_moderate = 'pending';

	return $userpoint;
}

/**
 * Add points to a user
 *
 * @param integer  $guid User Guid
 * @param integer  $points The number of points to add
 * @param string   $description Description for these points
 * @param string   $type The entity type that the points are being awarded for
 * @param integer  $guid The entity guid
 *
 * @return false|Userpoint Return false on failure or Userpoint on success
 */
function elggx_userpoints_add($user_guid, $points, $description, $type = null, $guid = null) {

	$points = (int) $points;

	// Create and save our new Userpoint object
	$userpoint = new Userpoint();
	$userpoint->owner_guid = $user_guid;
	$userpoint->container_guid = $user_guid;
	$userpoint->description = $description;
	$userpoint->save();

	// Just in case the save fails
	if (!$userpoint->guid) {
		return false;
	}

	// Add the points, type, and guid as metadata to the user object
	$userpoint->meta_points = $points;
	$userpoint->meta_type = $type;
	$userpoint->meta_guid = $guid;

	if (!elgg_trigger_plugin_hook('userpoints:add', $userpoint->type, ['entity' => $userpoint], true)) {
		$userpoint->delete();
		return false;
	}

	// If moderation is enabled set points to pending else they are auto approved
	if (elgg_get_plugin_setting('moderate', 'elggx_userpoints') && $type != 'admin') {
		$userpoint->meta_moderate = 'pending';
	} else {
		$userpoint->meta_moderate = 'approved';
		elggx_userpoints_update_user($user_guid, $points);
	}

	// Setup point expiration if enabled
	if (elgg_get_plugin_setting('expire_after', 'elggx_userpoints')) {
		if (function_exists('expirationdate_set')) {
			$ts = time() + elgg_get_plugin_setting('expire_after', 'elggx_userpoints');
			expirationdate_set($userpoint->guid, date('Y-m-d H:i:s', $ts), false);
		}
	}

	// Display a system message to the user if configured to do so
	if (elgg_get_plugin_setting('displaymessage', 'elggx_userpoints') && $type != 'admin' && $user_guid == elgg_get_logged_in_user_guid()) {
		$branding = ($points == 1) ? elgg_echo('elggx_userpoints:lowersingular') : elgg_echo('elggx_userpoints:lowerplural');
		$message = elgg_get_plugin_setting('moderate', 'elggx_userpoints') ? 'elggx_userpoints:pending_message' : 'elggx_userpoints:awarded_message';
		system_message(elgg_echo($message, [$points, $branding]));
	}

	return $userpoint;
}

/**
 * Subtract points from a user. This is just a wrapper around
 * elggx_userpoints_add as we are really just adding negative x points.
 *
 * @param integer  $guid User Guid
 * @param integer  $points The number of points to subtract
 * @param string   $description Description for these points
 * @param string   $type The entity type that the points are being awarded for
 * @param integer  $guid The entity guid
 *
 * @return false|Userpoint return false on failure or Userpoint on success
 */
function elggx_userpoints_subtract($user_guid, $points, $description, $type = null, $guid = null) {

	$points = (int) $points;
	if ($points > 0) {
		$points = -$points;
	}

	return elggx_userpoints_add($user_guid, $points, $description, $type, $guid);
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
 *
 * @return void|true return true in case of userpoint
 */
function elggx_userpoints_expire($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	if (!($entity instanceof Userpoint)) {
		return;
	}

	// Decrement the users total points
	elggx_userpoints_update_user($entity->owner_guid, -$entity->meta_points);

	return true;
}

/**
 * Given a user id, type, and entity id check to see if points have
 * already been awarded.
 *
 * @param  integer  $user_guid User Guid
 * @param  string   $type The entity type that the points are being awarded for
 * @param  integer  $guid The entity guid
 *
 * @return bool
 */
function elggx_userpoints_exists($user_guid, $type, $guid) {
	
	return (bool) elgg_get_entities([
		'type' => 'object',
		'subtype' => Userpoint::SUBTYPE,
		'owner_guid' => $user_guid,
		'count' => true,
		'metadata_name_value_pairs' => [
			[
				'name' => 'meta_type',
				'value' => $type,
				'operand' => '=',
			],
			[
				'name' => 'meta_guid',
				'value' => $guid,
				'operand' => '=',
			],
		],
	]);
}

/**
 * Returns a count of approved and pending points for the given user.
 *
 * @param  integer  $user_guid The user Guid
 *
 * @return array    An array including the count of approved/pending points
 */
function elggx_userpoints_get($user_guid) {

	$points = [
		'approved' => 0,
		'pending' => 0,
	];

	$entities = elgg_get_entities([
		'metadata_name' => 'meta_points',
		'type' => 'object',
		'subtype' => Userpoint::SUBTYPE,
		'owner_guid' => $user_guid,
		'limit' => false,
		'batch' => true,
	]);

	/* @var $obj Userpoint */
	foreach ($entities as $obj) {
		if (isset($obj->meta_moderate)) {
			if ($obj->meta_moderate === 'approved') {
				$points['approved'] += $obj->meta_points;
			} else if ($obj->meta_moderate === 'pending') {
				$points['pending'] += $obj->meta_points;
			}
		} else {
			$points['approved'] += $obj->meta_points;
		}
	}
	
	return $points;
}

/**
 * Deletes all userpoint record awarded for annotations or metadata based on
 * the owner, entity_guid and type and description. This method
 * should be called by plugins that want to delete points if the
 * content/object that awarded the points is deleted.
 *
 * Deletes a single or all matching userpoint records based on the owner user guid and guid/entity_guid the userpoint record is aligned with.
 * Optionally, the type and description can be provided to refine the deletion further.
 * This method should be called by plugins that want to delete points if the
 * content/object that awarded the points is deleted.
 *
 * @param  integer  $user_guid The owner user guid
 * @param  integer  $meta_guid The guid of the object the userpoint record belongs to,
 *                  or the entity_guid of the object the annotation or metadata belongs to,
 *                  or guid_two of the relationship the userpoint record belongs to
 * @param  string   $type The data type or null; can be either entity subtype, or annotation, or metadata, or relationship
 * @param  string   $description The userpoint record description string or null; can be either the entity subtype,
 *                  or annotation name, or metadata name, or a specific description string matching the userpoints record description
 * @param  integer  $limit The maximum number of userpoints record to delete; either positive int or for no limit either 0 or false
 *
 * @return false|void
 */
function elggx_userpoints_delete($user_guid, $meta_guid, $type = null, $description = null, $limit = false) {

	if (!elgg_get_plugin_setting('delete', 'elggx_userpoints')) {
		return false;
	}

	$user = get_user($user_guid);
	if ($user instanceof ElggUser) {

		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use($user_guid, $description, $type, $meta_guid, $limit) {
			$points = 0;

			$entities = elgg_get_entities([
				'type' => 'object',
				'subtype' => Userpoint::SUBTYPE,
				'owner_guid' => $user_guid,
				'attribute_name_value_pairs' => [
					'name' => 'description',
					'value' => $description,
					'operand' => '=',
					'case_sensitive' => true,
				],
				'metadata_name_value_pairs' => [
					[
						'name' => 'meta_type',
						'value' => $type,
						'operand' => '=',
					],
					[
						'name' => 'meta_guid',
						'value' => $meta_guid,
						'operand' => '=',
					],
				],
				'limit' => $limit,
				'batch' => true,
				'batch_inc_offset' => false,
			]);
			/* @var $entity Userpoint */
			foreach ($entities as $entity) {
				$points = $points + $entity->meta_points;
				$entity->delete();
			}

			// Decrement the users total points
			elggx_userpoints_update_user($user_guid, -$points);
		});
	}
}

/**
 * Deletes userpoint records based on the guid of the entity getting deleted
 * (=meta_guid of userpoint entities). This method
 * should be called by plugins that want to delete points if the
 * content/object that awarded the points is deleted.
 *
 * @param  integer  $guid The guid of the object being deleted
 *
 * @return false|void
 */
function elggx_userpoints_delete_by_meta_guid($guid) {

	if (!elgg_get_plugin_setting('delete', 'elggx_userpoints')) {
		return false;
	}

	elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use($guid) {
		$points_array = [];

		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => Userpoint::SUBTYPE,
			'metadata_name' => 'meta_guid',
			'metadata_value' => $guid,
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);
		/* @var $entity Userpoint */
		foreach ($entities as $entity) {
			if (array_key_exists($entity->owner_guid, $points_array)) {
				$points_array[$entity->owner_guid] += $entity->meta_points;
			} else {
				$points_array[$entity->owner_guid] = $entity->meta_points;
			}
			$entity->delete();
		}

		// Decrement the users total points
		foreach ($points_array as $user_guid => $points) {
			elggx_userpoints_update_user($user_guid, -$points);
		}
	});
}

/**
 * Deletes userpoints by the guid of the userpoint entity.
 * This method is called when administratively deleting points
 * or when points expire.
 *
 * @param  integer  $guid The guid of the userpoint entity
 */
function elggx_userpoints_delete_by_userpoint($guid) {

	$entity = get_entity($guid);
	if (!($entity instanceof Userpoint)) {
		return;
	}
	
	$owner_guid = $entity->owner_guid;
	$points = $entity->meta_points;

	// Delete the userpoint entity
	$entity->delete();

	// Decrement the users total points
	elggx_userpoints_update_user($owner_guid, -$points);
}

/**
 * Update the users running points total
 *
 * @param  integer  $guid   The guid of the userpoint entity
 * @param  integer  $points The number of points to be added (or subtracted if a negative value)
 *
 * @return bool
 */
function elggx_userpoints_update_user($guid, $points) {
	$user = get_user($guid);
	$points = (int) $points;

	if (empty($user)) {
		return false;
	}

	if (is_int($user->userpoints_points)) {
		$user->userpoints_points = (int) $user->userpoints_points + $points;
	} else {
		elgg_delete_metadata([
			'guid' => $user->guid,
			'metadata_name' => 'userpoints_points',
		]);

		$users_points = elggx_userpoints_get($user->guid);
		$users_approved_points = $users_points['approved'];
		$user->userpoints_points = (int) $users_approved_points;
	}

	if (!elgg_trigger_plugin_hook('userpoints:update', 'object', ['entity' => $user], true)) {
		$user->userpoints_points = (int) $user->userpoints_points - $points;
		return false;
	}
	
	return true;
}

/**
 * Awards pending userpoints by the guid of the userpoint entity.
 * This method is called when administratively moderating points
 * or when points expire.
 *
 * @param  integer  $guid The guid of the userpoint entity
 */
function elggx_userpoints_moderate($guid, $status) {

	$entity = get_entity($guid);
	if (!($entity instanceof Userpoint)) {
		return;
	}
	
	$entity->meta_moderate = $status;

	// increment the users total points if approved
	if ($status === 'approved') {
		elggx_userpoints_update_user($entity->owner_guid, $entity->meta_points);
	}
}

/**
 * Check for an existing pending invite for the given email address.
 *
 * @param string   $email The amail address of the invited user
 *
 * @return bool    Return true/false on pending record found or not
 */
function elggx_userpoints_registration_award($email) {

	elgg_call(ELGG_IGNORE_ACCESS, function () use($email) {
		$guids = elggx_userpoints_invite_status(null, $email);

		if (empty($guids)) {
			return;
		}

		foreach ($guids as $guid) {
			$entity = get_entity($guid);
			$entity->meta_moderate = 'approved';

			$user = get_user($entity->owner_guid);

			if (is_int($user->userpoints_points)) {
				$user->userpoints_points = (int) $user->userpoints_points + (int) $entity->meta_points;
			} else {
				elgg_delete_metadata([
					'guid' => $user->guid,
					'metadata_name' => 'userpoints_points',
				]);

				$users_points = elggx_userpoints_get($user->guid);
				$users_approved_points = $users_points['approved'];
				$user->userpoints_points = (int)$users_approved_points;
			}

			if (!elgg_trigger_plugin_hook('userpoints:update', 'object', ['entity' => $user], true)) {
				$user->userpoints_points = (int) $user->userpoints_points - (int) $entity->meta_points;
			}
		}
	});
}

/**
 * Check for an existing pending invite for the given email address.
 *
 * @param integer  $guid The inviting users guid
 * @param string   $email The amail address of the invited user
 *
 * @return mixed   Return userpoint guid on pending otherwise return moderation status or false if no record
 */
function elggx_userpoints_invite_status($guid = null, $email) {

	$status = elgg_call(ELGG_IGNORE_ACCESS, function () use($guid, $email) {
		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => Userpoint::SUBTYPE,
			'owner_guid' => $guid,
			'limit' => false,
			'metadata_name_value_pairs' => [
				[
					'name' => 'meta_type',
					'value' => 'invite',
					'operand' => '=',
				],
				[
					'name' => 'meta_moderate',
					'value' => 'pending',
					'operand' => '=',
				],
			],
		]);
		if (empty($entities)) {
			return false;
		}

		$status = [];

		/* @var $entity Userpoint */
		foreach ($entities as $entity) {
			if ($entity->description === $email) {
				$status[] = $entity->guid;
			}
		}
	});

	if (empty($status)) {
		return false;
	}

	return $status;
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
