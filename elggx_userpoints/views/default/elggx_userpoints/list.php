<?php

$offset = get_input('offset') ? (int)get_input('offset') : 0;
$limit = 10;

$options_count = array('type' => 'user', 'limit' => false, 'count' => true, 'order_by_metadata' =>  array('name' => 'userpoints_points', 'direction' => DESC, 'as' => integer));
$options_count['metadata_name_value_pairs'] = array(array('name' => 'userpoints_points', 'value' => 0,  'operand' => '>'));
$count = elgg_get_entities_from_metadata($options_count);
$options = array('type' => 'user', 'limit' => $limit, 'offset' => $offset, 'order_by_metadata' =>  array('name' => 'userpoints_points', 'direction' => DESC, 'as' => integer));
$options['metadata_name_value_pairs'] = array(array('name' => 'userpoints_points', 'value' => 0,  'operand' => '>'));
$entities = elgg_get_entities_from_metadata($options);

$nav = elgg_view('navigation/pagination',array(
	'base_url' => elgg_get_site_url() . "admin/administer_utilities/elggx_userpoints?tab=list",
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit
));

$html = $nav;

$html .= "<div><br><table><tr><th width=\"50%\"><b>".elgg_echo('elggx_userpoints:user')."</b></th>";
$html .= "<th width=\"20%\"><b>".elgg_echo('elggx_userpoints:upperplural')."</b></th>";
$html .= "<th width=\"10%\"><b>".elgg_echo('elggx_userpoints:action')."</b></tr>";
$html .= "<tr><td colspan=3></td></tr>";

foreach ($entities as $entity) {

	$html .= "<tr><td><a href=\"" . elgg_get_site_url() . "admin/administer_utilities/elggx_userpoints?tab=detail&user_guid={$entity->guid}\">{$entity->username}</a></td>";
	$html .= "<td><a href=\"" . elgg_get_site_url() . "admin/administer_utilities/elggx_userpoints?tab=detail&user_guid={$entity->guid}\">{$entity->userpoints_points}</a></td>";
	$html .= "<td>" . elgg_view("output/confirmlink", array(
							'href' => elgg_get_site_url() . "action/elggx_userpoints/reset?user_guid={$entity->guid}",
							'text' => elgg_echo('elggx_userpoints:reset'),
							'is_action' => true,
							'is_trusted' => true,
							'confirm' => elgg_echo('elggx_userpoints:reset:confirm', array($entity->username))
						));
	$html .= "</td></tr>";

}
$html .= "<tr><td colspan=3></td></tr>";
$html .= "</table></div>";

$html .= "<br><br>";
$html .=  elgg_echo('elggx_userpoints:restore_help');
$form_body .= elgg_view('input/text', array('name' => 'username', 'class' => 'mbm'));
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('elggx_userpoints:restore')));
$action_url = elgg_get_site_url() . "action/elggx_userpoints/restore";
$html .= elgg_view('input/form', array('body' => $form_body, 'action' => $action_url, 'class' => 'mbl'));

$html .= "<div class='mbm'>" . elgg_echo('elggx_userpoints:restore_all_help') . "</div>";
$html .= elgg_view("output/confirmlink", array(
		'href' => elgg_get_site_url() . "action/elggx_userpoints/restore_all",
		'text' => elgg_echo('elggx_userpoints:restore_all'),
		'is_action' => true,
		'is_trusted' => true,
		'confirm' => elgg_echo('elggx_userpoints:restore_all:confirm'),
		'class' => 'elgg-button elgg-button-action'
));

echo $html;
