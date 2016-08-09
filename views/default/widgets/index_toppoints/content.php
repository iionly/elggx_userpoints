<?php
/**
 * Elggx Userpoints Plugin
 *
 * Index page Toppoints widget for Widget Manager plugin
 *
 */

// get widget settings
$limit = sanitise_int($vars["entity"]->toppoints_count, false);
if(empty($limit)) {
	$limit = 10;
}

$options = array('type' => 'user', 'limit' => $limit, 'order_by_metadata' =>  array('name' => 'userpoints_points', 'direction' => DESC, 'as' => integer));
$options['metadata_name_value_pairs'] = array(array('name' => 'userpoints_points', 'value' => 0,  'operand' => '>'));
$entities = elgg_get_entities_from_metadata($options);

$html = '<div><ul class="elgg-list elgg-list entity">';

foreach ($entities as $entity) {
	$icon = elgg_view_entity_icon($entity, 'small');
	$link_params = array(
		'href' => $entity->getUrl(),
		'text' => $entity->name,
	);

	// Simple XFN, see http://gmpg.org/xfn/
	if (elgg_get_logged_in_user_guid() == $entity->guid) {
		$link_params['rel'] = 'me';
	} elseif (check_entity_relationship(elgg_get_logged_in_user_guid(), 'friend', $entity->guid)) {
		$link_params['rel'] = 'friend';
	}
	$title = elgg_view('output/url', $link_params);

	if ($entity->isBanned()) {
		$banned = elgg_echo('banned');
		$params = array(
			'entity' => $entity,
			'title' => $title,
		);
	} else {
		$branding = (abs($entity->userpoints_points) > 1) ? elgg_echo('elggx_userpoints:lowerplural') : elgg_echo('elggx_userpoints:lowersingular');
		$params = array(
			'entity' => $entity,
			'title' => $title,
			'content' => "<b>{$entity->userpoints_points} $branding</b>",
		);
	}

	$list_body = elgg_view('user/elements/summary', $params);
	$html .= "<li class elgg-item elgg-item-user>" . elgg_view_image_block($icon, $list_body) . "</li>";
}

$html .= '</ul></div>';

echo $html;
