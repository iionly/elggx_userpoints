<?php

class ElggxUserpointsEvents {

	public static function elggx_userpoints_validate(\Elgg\Event $event) {
		$event_name = $event->getName();
		$object_type = $event->getType();
		$object = $event->getObject();

		if ($event_name === 'enable' && $object_type === 'user' && $object instanceof ElggUser) {
			\ElggxUserpointsFunctions::elggx_userpoints_registration_award($object->email);
		}
	}

	public static function elggx_userpoints_login(\Elgg\Event $event) {
		$user = $event->getObject();

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
		$time_since_last_login = time() - (int) $user->prev_last_login;

		if (($time_since_last_login < $interval) || !$user->prev_last_login) {

			// The login threshold has been met so now add the points
			if (!\ElggxUserpointsFunctions::elggx_userpoints_add($user->guid, $points, 'Login')) {
				return;
			}

			$user->userpoints_login = time();
			$user->prev_last_login = $user->userpoints_login;
		}
	}

	public static function elggx_userpoints_object(\Elgg\Event $event) {
		$event_name = $event->getName();
		$object = $event->getObject();

		if ($event_name == 'create') {
			$subtype = $object->getSubtype();
			if ($points = elgg_get_plugin_setting($subtype, 'elggx_userpoints')) {
				\ElggxUserpointsFunctions::elggx_userpoints_add($object->owner_guid, $points, $subtype, $subtype, $object->guid);
			}
		} else if ($event_name == 'delete') {
			\ElggxUserpointsFunctions::elggx_userpoints_delete_by_meta_guid($object->guid);
		}
	}

	public static function elggx_userpoints_annotate(\Elgg\Event $event) {
		$event_name = $event->getName();
		$object_type = $event->getType();
		$object = $event->getObject();

		$description = $object->name;
		if ($event_name == 'create') {
			if ($points = elgg_get_plugin_setting($description, 'elggx_userpoints')) {
				\ElggxUserpointsFunctions::elggx_userpoints_add($object->owner_guid, $points, $description, $object_type, $object->entity_guid);
			}
		} else if ($event_name == 'delete') {
			\ElggxUserpointsFunctions::elggx_userpoints_delete($object->owner_guid, $object->entity_guid, $object_type, $description, 1, true);
		}
	}

	public static function elggx_userpoints_group(\Elgg\Event $event) {
		$event_name = $event->getName();
		$object_type = $event->getType();
		$object = $event->getObject();

		if ($event_name == 'create') {
			if ($points = elgg_get_plugin_setting($object_type, 'elggx_userpoints')) {
				\ElggxUserpointsFunctions::elggx_userpoints_add($object->owner_guid, $points, $object_type, $object_type, $object->guid);
			}
		} else if ($event_name == 'delete') {
			\ElggxUserpointsFunctions::elggx_userpoints_delete_by_meta_guid($object->guid);
		}
	}

	public static function elggx_userpoints_profile(\Elgg\Event $event) {
		$user = $event->getObject();

		$points = (int) elgg_get_plugin_setting('profileupdate', 'elggx_userpoints');
		if (empty($points)) {
			// no point configured for updating the profile
			return;
		}

		// Check to see if the configured amount of time has passed
		// before awarding more points for updating the profile
		$diff = time() - (int) $user->userpoints_profileupdate;
		if ($diff < (int) elgg_get_plugin_setting('profileupdate_threshold', 'elggx_userpoints')) {
			return;
		}

		// The login threshold has been met so now add the points
		if (!\ElggxUserpointsFunctions::elggx_userpoints_add($user->guid, $points, 'Profileupdate')) {
			return;
		}

		$user->userpoints_profileupdate = time();
	}

	public static function elggx_userpoints_profileiconupdate(\Elgg\Event $event) {
		$user = $event->getObject();

		$points = (int) elgg_get_plugin_setting('profileicon', 'elggx_userpoints');
		if (empty($points)) {
			// no point configured for updating the profileicon
			return;
		}

		// Check to see if the configured amount of time has passed
		// before awarding more points for updating the profileicon
		$diff = time() - (int) $user->userpoints_profileiconupdate;
		if ($diff < (int) elgg_get_plugin_setting('profileiconupdate_threshold', 'elggx_userpoints')) {
			return;
		}

		// The login threshold has been met so now add the points
		if (!\ElggxUserpointsFunctions::elggx_userpoints_add($user->guid, $points, 'Profileiconupdate')) {
			return;
		}

		$user->userpoints_profileiconupdate = time();
	}

	public static function elggx_userpoints_relationship(\Elgg\Event $event) {
		$event_name = $event->getName();
		$object_type = $event->getType();
		$object = $event->getObject();

		if ($object_type !== 'relationship' || $object->relationship !== 'friend') {
			return;
		}

		$subject_user = get_user($object->guid_one);
		$object_user = get_user($object->guid_two);

		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use($event_name, $object_type, $subject_user, $object_user) {
			if ($event_name == 'create') {
				$points = (int) elgg_get_plugin_setting('friend', 'elggx_userpoints');
				if (empty($points)) {
					// no point configured for friending
					return;
				}
				\ElggxUserpointsFunctions::elggx_userpoints_add($subject_user->guid, $points, 'Befriending', $object_type, $object_user->guid);
			} else if ($event_name == 'delete') {
				\ElggxUserpointsFunctions::elggx_userpoints_delete($subject_user->guid, $object_user->guid, $object_type, 'Befriending', 1);
			}
		});
	}

	/**
	* Hooks on the invitefriends/invite action and either awards
	* points for the invite or sets up a pending userpoint record
	* where points can be awarded when the invited user registers.
	*/
	public static function elggx_userpoints_invite(\Elgg\Event $event) {
		if (!$points = elgg_get_plugin_setting('invite', 'elggx_userpoints')) {
			return;
		}

		$emails = get_input('emails');
		$emails = explode("\n", $emails);

		if (empty($emails)) {
			return;
		}
		
		foreach ($emails as $email) {

			$email = trim($email);
			if (get_user_by_email($email)) {
				continue;
			}

			if (elgg_get_plugin_setting('verify_email', 'elggx_userpoints') && !elggx_userpoints_validEmail($email)) {
				continue;
			}

			if ((int)elgg_get_plugin_setting('require_registration', 'elggx_userpoints')) {
				if (!\ElggxUserpointsFunctions::elggx_userpoints_invite_status(elgg_get_logged_in_user_guid(), $email)) {
					$userpoint = \ElggxUserpointsFunctions::elggx_userpoints_add_pending(elgg_get_logged_in_user_guid(), $points, $email, 'invite');
					if (elgg_is_active_plugin('expirationdate') && $expire = (int)elgg_get_plugin_setting('expire_invite', 'elggx_userpoints')) {
						$ts = time() + $expire;
						expirationdate_set($userpoint->guid, date('Y-m-d H:i:s', $ts), false);
					}
				}
			} else {
				if (!\ElggxUserpointsFunctions::elggx_userpoints_invite_status(elgg_get_logged_in_user_guid(), $email)) {
					\ElggxUserpointsFunctions::elggx_userpoints_add(elgg_get_logged_in_user_guid(), $points, $email, 'invite');
					$userpoint = \ElggxUserpointsFunctions::elggx_userpoints_add_pending(elgg_get_logged_in_user_guid(), 0, $email, 'invite');
					if (elgg_is_active_plugin('expirationdate') && $expire = (int)elgg_get_plugin_setting('expire_invite', 'elggx_userpoints')) {
						$ts = time() + $expire;
						expirationdate_set($userpoint->guid, date('Y-m-d H:i:s', $ts), false);
					}
				}
			}
		}
	}

	/**
	* Hooks on the register action and checks to see if the inviting
	* user has a pending userpoints record for the invited user. If
	* the uservalidationbyemail plugin is enabled then points will
	* not be awarded until the invited user verifies their email
	* address.
	*/
	public static function elggx_userpoints_register(\Elgg\Event $event) {
		$email = get_input('email');

		if (elgg_is_active_plugin('uservalidationbyemail')) {
			return;
		}

		// No email validation configured so award the points
		\ElggxUserpointsFunctions::elggx_userpoints_registration_award($email);

		return;
	}

	/**
	* Appends "elggx_userpoints" tab to the navigation on the members page of bundled Members plugin
	*/
	public static function elggx_userpoints_members_nav(\Elgg\Event $event) {
		$result = $event->getValue();
		$filter_value = $event->getParam('filter_value');
		
		$result['elggx_userpoints'] = \ElggMenuItem::factory([
			'name' => 'elggx_userpoints',
			'text' => elgg_echo('sort:elggx_userpoints'),
			'href' => elgg_generate_url('collection:user:user:elggx_userpoints'),
			'selected' => $filter_value === 'elggx_userpoints',
			'priority' => 501,
		]);

		return $result;
	}

	/**
	* Change the entity menu for a Userpoint
	*/
	public static function elggx_userpoints_entity_menu(\Elgg\Event $event) {
		$returnvalue = $event->getValue();
		$entity = $event->getParam('entity');

		if (!($entity instanceof Userpoint)) {
			return;
		}

		// remove some items
		$remove_items = [
			'edit',
		];
		foreach ($returnvalue as $index => $menu_item) {
			if (!in_array($menu_item->getName(), $remove_items)) {
				continue;
			}
			
			unset($returnvalue[$index]);
		}

		// add moderation links
		if (elgg_is_admin_logged_in() && $entity->meta_moderate === 'pending') {
			$returnvalue[] = ElggMenuItem::factory([
				'name' => 'elggx_userpoints_approved',
				'text' => elgg_echo('elggx_userpoints:approved'),
				'href' => elgg_http_add_url_query_elements('action/elggx_userpoints/moderate', [
					'guid' => $entity->guid,
					'status' => 'approved',
				]),
				'is_action' => true,
				'priority' => 100,
			]);
			$returnvalue[] = ElggMenuItem::factory([
				'name' => 'elggx_userpoints_denied',
				'text' => elgg_echo('elggx_userpoints:denied'),
				'href' => elgg_http_add_url_query_elements('action/elggx_userpoints/moderate', [
					'guid' => $entity->guid,
					'status' => 'denied',
				]),
				'is_action' => true,
				'priority' => 101,
			]);
		}

		return $returnvalue;
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
	public static function elggx_userpoints_expire(\Elgg\Event $event) {
		$entity = $event->getParam('entity');
		
		if (!($entity instanceof Userpoint)) {
			return;
		}

		// Decrement the users total points
		\ElggxUserpointsFunctions::elggx_userpoints_update_user($entity->owner_guid, -$entity->meta_points);

		return true;
	}
}
