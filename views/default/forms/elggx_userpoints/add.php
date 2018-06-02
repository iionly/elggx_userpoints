<?php

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('elggx_userpoints:add:user'),
	'name' => 'params[username]',
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('elggx_userpoints:add:points'),
	'name' => 'params[points]',
	'required' => true,
	'min' => 0,
	'step' => 1,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('elggx_userpoints:add:description'),
	'name' => 'params[description]',
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
