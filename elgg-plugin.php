<?php

require_once(dirname(__FILE__) . '/lib/userpoint.php');
require_once(dirname(__FILE__) . '/lib/events.php');
require_once(dirname(__FILE__) . '/lib/hooks.php');

return [
	'bootstrap' => \ElggxUserpointsBootstrap::class,
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'userpoint',
			'class' => 'Userpoint',
			'searchable' => false,
		],
	],
	'actions' => [
		'elggx_userpoints/settings' => ['access' => 'admin'],
		'elggx_userpoints/delete' => ['access' => 'admin'],
		'elggx_userpoints/moderate' => ['access' => 'admin'],
		'elggx_userpoints/add' => ['access' => 'admin'],
		'elggx_userpoints/reset' => ['access' => 'admin'],
		'elggx_userpoints/restore' => ['access' => 'admin'],
		'elggx_userpoints/restore_all' => ['access' => 'admin'],
	],
	'routes' => [
		'collection:user:user:elggx_userpoints' => [
			'path' => '/members/elggx_userpoints',
			'resource' => 'members/elggx_userpoints',
		],
	],
	'widgets' => [
		'toppoints' => [
			'context' => ['profile', 'dashboard'],
		],
		'index_toppoints' => [
			'context' => ['index'],
		],
	],
];
