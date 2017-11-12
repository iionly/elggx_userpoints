<?php
/**
 * Object view for userpoint
 *
 * @uses $vars['entity'] the userpoint to view
 */

/* @var $entity Userpoint */
$entity = elgg_extract('entity', $vars);

$owner = $entity->getOwnerEntity();

$icon = elgg_view_entity_icon($owner, 'small');
$subtitle = elgg_view('page/elements/by_line', [
	'entity' => $entity,
]);

$entity_menu = elgg_view_menu('entity', [
	'entity' => $entity,
	'class' => 'elgg-menu-hz',
	'sort_by' => 'priority',
	'handler' => 'elggx_userpoints',
]);

// content
$points = (int) $entity->meta_points;
$postfix = ($points == 1) ? elgg_echo('elggx_userpoints:lowersingular') : elgg_echo('elggx_userpoints:lowerplural');

$content = [];
$content[] = elgg_view('output/url', [
	'text' => "{$points} {$postfix}",
	'href' => $entity->getURL(),
]);

$content[] = elgg_echo('elggx_userpoints:awarded_for') . ':';

$content[] = $entity->description;

if ($entity->meta_guid) {
	$from = get_entity($entity->meta_guid);
	if ($from instanceof ElggEntity) {
		$content[] = '<br>' . elgg_view('output/url', [
			'text' => $from->getDisplayName() ?: elgg_get_excerpt($from->description, 100),
			'href' => $from->getURL(),
		]);
	}
}

// output
echo elgg_view('object/elements/summary', [
	'entity' => $entity,
	'subtitle' => $subtitle,
	'metadata' => $entity_menu,
	'icon' => $icon,
	'content' => implode(' ', $content),
]);
