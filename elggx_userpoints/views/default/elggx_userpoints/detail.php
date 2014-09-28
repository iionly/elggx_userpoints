<?php

$offset = (int)get_input('offset');
$user_guid = get_input('user_guid');

$limit = 10;

$count = elgg_get_entities_from_metadata(array(
	'metadata_name' => 'meta_moderate',
	'metadata_value' => 'approved',
	'type' => 'object',
	'subtype' => 'userpoint',
	'owner_guid' => $user_guid,
	'limit' => false,
	'offset' => 0,
	'count' => true
));
$entities = elgg_get_entities_from_metadata(array(
	'metadata_name' => 'meta_moderate',
	'metadata_value' => 'approved',
	'type' => 'object',
	'subtype' => 'userpoint',
	'owner_guid' => $user_guid,
	'limit' => $limit,
	'offset' => $offset
));

$base_url = elgg_get_site_url() . "admin/administer_utilities/elggx_userpoints?tab=detail";
if ($user_guid) {
	$base_url .= "&user_guid=$user_guid";
}
$nav = elgg_view('navigation/pagination',array(
	'base_url' => $base_url,
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit
));

$html = $nav;

if ($count=='0') {
	$html .= "<br>" . elgg_echo('elggx_userpoints:approved_empty');
} else {

	$html .= "<ul class=\"elgg-list-distinct\">";

	foreach ($entities as $entity) {

		$html .= "<li class=\"elgg-item\">";

		$owner = $entity->getOwnerEntity();
		$friendlytime = elgg_view_friendly_time($entity->time_created);

		$owner_link = elgg_view('output/url', array(
			'href' => "poll/owner/$owner->username",
			'text' => $owner->name,
			'is_trusted' => true,
		));
		$author_text = elgg_echo('byline', array($owner_link));

		$points = $entity->meta_points;
		$v = ($points == 1) ? elgg_echo('elggx_userpoints:lowersingular') : elgg_echo('elggx_userpoints:lowerplural');

		$icon = elgg_view_entity_icon($owner, 'small');

		// Initialize link to the description on the Userpoint
		$link = $entity->description;

		// If we have the parent ID and its a valid object build a link to it.
		if (isset($entity->meta_guid)) {
			$parent = get_entity($entity->meta_guid);
			if (is_object($parent)) {
				$item = $parent->title ? $parent->title : $parent->description;
				$link = "<a href=\"{$parent->getURL()}\">$item</a>";
			}
		}

		$info = elgg_view("output/longtext", array('value' => "{$author_text} {$friendlytime}", 'class' => 'elgg-subtext'));
		$info .= "<p><a href=\"{$entity->getURL()}\">{$points} $v</a> " . elgg_echo('elggx_userpoints:awarded_for') . " {$entity->meta_type}: $link</p>";
		$info .= "(<a href=\"" . elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/elggx_userpoints/delete?guid={$entity->guid}") . "\">".elgg_echo('elggx_userpoints:delete')."</a>)";

		$html .= elgg_view('page/components/image_block', array('image' => $icon, 'body' => $info));
		$html .= "</li>";
	}
	$html .= "</ul>";
}

echo $html;
