<?php

echo elgg_view('output/longtext', [
	'value' => elgg_echo('elggx_userpoints:restore_help'),
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('elggx_userpoints:restore_help:label'),
	'name' => 'username',
	'required' => true,
]);

// form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('elggx_userpoints:restore'),
]);

elgg_set_form_footer($footer);
