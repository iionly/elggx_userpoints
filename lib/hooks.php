<?php

/**
 * Hooks on the invitefriends/invite action and either awards
 * points for the invite or sets up a pending userpoint record
 * where points can be awarded when the invited user registers.
 */
function elggx_userpoints_invite(\Elgg\Hook $hook) {
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
			if (!elggx_userpoints_invite_status(elgg_get_logged_in_user_guid(), $email)) {
				$userpoint = elggx_userpoints_add_pending(elgg_get_logged_in_user_guid(), $points, $email, 'invite');
				if (elgg_is_active_plugin('expirationdate') && $expire = (int)elgg_get_plugin_setting('expire_invite', 'elggx_userpoints')) {
					$ts = time() + $expire;
					expirationdate_set($userpoint->guid, date('Y-m-d H:i:s', $ts), false);
				}
			}
		} else {
			if (!elggx_userpoints_invite_status(elgg_get_logged_in_user_guid(), $email)) {
				elggx_userpoints_add(elgg_get_logged_in_user_guid(), $points, $email, 'invite');
				$userpoint = elggx_userpoints_add_pending(elgg_get_logged_in_user_guid(), 0, $email, 'invite');
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
function elggx_userpoints_register(\Elgg\Hook $hook) {
	$email = get_input('email');

	if (elgg_is_active_plugin('uservalidationbyemail')) {
		return;
	}

	// No email validation configured so award the points
	elggx_userpoints_registration_award($email);

	return;
}

/**
 * Appends "elggx_userpoints" tab to the navigation on the members page of bundled Members plugin
 */
function elggx_userpoints_members_nav(\Elgg\Hook $hook) {
	$result = $hook->getValue();
	$filter_value = $hook->getParam('filter_value');
	
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
function elggx_userpoints_entity_menu(\Elgg\Hook $hook) {
	$returnvalue = $hook->getValue();
	$entity = $hook->getParam('entity');

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
