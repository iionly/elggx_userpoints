<?php

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
function elggx_userpoints_register() {

	$email = get_input('email');

	if (elgg_is_active_plugin('uservalidationbyemail')) {
		return;
	}

	// No email validation configured so award the points
	elggx_userpoints_registration_award($email);

	return;
}

/**
 * Returns content for the "elggx_userpoints" tab page of the members page of  bundled Members plugin
 *
 * @param string      $hook        "members:list"
 * @param string      $type        "elggx_userpoints"
 * @param string|null $returnvalue list content (null if not set)
 * @param array       $params      array with key "options"
 *
 * @return string
 */
function elggx_userpoints_members_list($hook, $type, $returnvalue, $params) {

	if ($returnvalue !== null) {
		return;
	}

	$limit = (int) max(get_input('limit', elgg_get_config('default_limit')), 0);
	$offset = (int) max(get_input('offset', 0), 0);

	$count = elgg_get_entities_from_metadata([
		'type' => 'user',
		'limit' => false,
		'count' => true,
		'order_by_metadata' => [
			'name' => 'userpoints_points',
			'direction' => 'DESC',
			'as' => 'integer',
		],
		'metadata_name_value_pairs' => [
			'name' => 'userpoints_points',
			'value' => 0,
			'operand' => '>',
		],
	]);
	
	$options = $params['options'];
	
	$options['limit'] = $limit;
	$options['offset'] = $offset;
	$options['type'] = 'user';
	$options['order_by_metadata'] = [
		'name' => 'userpoints_points',
		'direction' => 'DESC',
		'as' => 'integer',
	];
	$options['metadata_name_value_pairs'] = [
		'name' => 'userpoints_points',
		'value' => 0,
		'operand' => '>',
	];
	$entities = elgg_get_entities_from_metadata($options);

	$html = '<div><ul class="elgg-list elgg-list entity">';

	foreach ($entities as $entity) {
		$icon = elgg_view_entity_icon($entity, 'tiny');
		$link_params = [
			'href' => $entity->getUrl(),
			'text' => $entity->getDisplayName(),
		];

		// Simple XFN, see http://gmpg.org/xfn/
		if (elgg_get_logged_in_user_guid() == $entity->guid) {
			$link_params['rel'] = 'me';
		} elseif (check_entity_relationship(elgg_get_logged_in_user_guid(), 'friend', $entity->guid)) {
			$link_params['rel'] = 'friend';
		}
		$title = elgg_view('output/url', $link_params);

		if ($entity->isBanned()) {
			$banned = elgg_echo('banned');
			$view_params = [
				'entity' => $entity,
				'title' => $title,
			];
		} else {
			$branding = (abs($entity->userpoints_points) > 1) ? elgg_echo('elggx_userpoints:lowerplural') : elgg_echo('elggx_userpoints:lowersingular');
			$view_params = [
				'entity' => $entity,
				'title' => $title,
				'content' => "<b>{$entity->userpoints_points} $branding</b>",
			];
		}

		$list_body = elgg_view('user/elements/summary', $view_params);
		$html .= "<li class elgg-item elgg-item-user>" . elgg_view_image_block($icon, $list_body) . "</li>";
	}

	$html .= '</ul></div>';

	$html .= elgg_view('navigation/pagination', [
		'base_url' => elgg_get_site_url() . "members/elggx_userpoints",
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
	]);

	return $html;
}

/**
 * Appends "elggx_userpoints" tab to the navigation on the members page of bundled Members plugin
 *
 * @param string $hook        "members:config"
 * @param string $type        "tabs"
 * @param array  $returnvalue array that build navigation tabs
 * @param array  $params      unused
 *
 * @return array
 */
function elggx_userpoints_members_nav($hook, $type, $returnvalue, $params) {

	$returnvalue['elggx_userpoints'] = [
		'title' => elgg_echo('sort:elggx_userpoints'),
		'url' => "members/elggx_userpoints",
	];
	
	return $returnvalue;
}

/**
 * Change the entity menu for a Userpoint
 *
 * @param string         $hook        the name of the hook
 * @param string         $type        the type of the hook
 * @param ElggMenuItem[] $returnvalue current return value
 * @param array          $params      supplied params
 *
 * @return void|ElggMenuItem[]
 */
function elggx_userpoints_entity_menu($hook, $type, $returnvalue, $params) {

	$entity = elgg_extract('entity', $params);
	if (!($entity instanceof Userpoint)) {
		return;
	}

	// remove some items
	$remove_items = [
		'edit',
		'access',
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
