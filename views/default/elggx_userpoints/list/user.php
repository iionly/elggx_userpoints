<?php
/**
 * Listing view for a user with points
 *
 * @uses $vars['entity'] the user to list
 */

/* @var $entity ElggUser */
$entity = elgg_extract('entity', $vars);

$icon = elgg_view_entity_icon($entity, 'small');

$link_params = [
	'href' => $entity->getUrl(),
	'text' => $entity->getDisplayName(),
	'is_trusted' => true,
];

// Simple XFN, see http://gmpg.org/xfn/
if (elgg_get_logged_in_user_guid() === $entity->guid) {
	$link_params['rel'] = 'me';
} elseif (check_entity_relationship(elgg_get_logged_in_user_guid(), 'friend', $entity->guid)) {
	$link_params['rel'] = 'friend';
}

$params = [
	'entity' => $entity,
	'subtitle' => '',
	'title' => elgg_view('output/url', $link_params),
];

if (!$entity->isBanned()) {
	$branding = (abs($entity->userpoints_points) > 1) ? elgg_echo('elggx_userpoints:lowerplural') : elgg_echo('elggx_userpoints:lowersingular');
	
	$params['content'] = elgg_format_element('b', [], "{$entity->userpoints_points} $branding");
}

$list_body = elgg_view('user/elements/summary', $params);

echo elgg_view_image_block($icon, $list_body);
