<?php
/**
 * Set the points for the different actions
 *
 * Since the settings are saved in plugin settings (and using that actions) make sure your input names are 'params[setting_name]'
 *
 * @uses $vars['entity'] the ElggPlugin from which you can retrieve the settings
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'plugin_id',
	'value' => 'elggx_userpoints',
]);

// here the different parts can extend the form
// use elgg_extend_view('forms/elggx_userpoints/actions', 'your_plugin/your_settings');

// form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
