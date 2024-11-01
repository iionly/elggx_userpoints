<?php

return [
	'plugin' => [
		'name' => 'Elggx Userpoints',
		'version' => '5.0.0',
	],
	'bootstrap' => \ElggxUserpointsBootstrap::class,
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'userpoint',
			'class' => 'Userpoint',
			'capabilities' => [
				'commentable' => false,
				'searchable' => false,
				'likable' => false,
			],
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
	'settings' => [
		'moderate' => '1',
		'profile_display' => '1',
		'displaymessage' => '1',
		'subtract' => '0',
		'delete' => '0',
		'expire_after' => '0',
	],
	'events' => [
		'register' => [
			'menu:entity' => [
				"\ElggxUserpointsEvents::elggx_userpoints_entity_menu" => [],
			],
			'menu:filter:members' => [
				"\ElggxUserpointsEvents::elggx_userpoints_members_nav" => [],
			],
		],
		'expirationdate:expire_entity' => [
			'all' => [
				"\ElggxUserpointsEvents::elggx_userpoints_expire" => [],
			],
		],
		'action' => [
			'invitefriends/invite' => [
				"\ElggxUserpointsEvents::elggx_userpoints_invite" => [],
			],
		],
		'action:validate' => [
			'register' => [
				"\ElggxUserpointsEvents::elggx_userpoints_register" => [],
			],
		],
		'enable' => [
			'user' => [
				"\ElggxUserpointsEvents::elggx_userpoints_validate" => [],
			],
		],
		'login:after' => [
			'user' => [
				"\ElggxUserpointsEvents::elggx_userpoints_login" => [],
			],
		],
		'create' => [
			'object' => [
				"\ElggxUserpointsEvents::elggx_userpoints_object" => [],
			],
			'annotation' => [
				"\ElggxUserpointsEvents::elggx_userpoints_annotate" => [],
			],
			'relationship' => [
				"\ElggxUserpointsEvents::elggx_userpoints_relationship" => [],
			],
			'group' => [
				"\ElggxUserpointsEvents::elggx_userpoints_group" => [],
			],
		],
		'delete' => [
			'object' => [
				"\ElggxUserpointsEvents::elggx_userpoints_object" => [],
			],
			'entity' => [
				"\ElggxUserpointsEvents::elggx_userpoints_object" => [],
			],
			'annotation' => [
				"\ElggxUserpointsEvents::elggx_userpoints_annotate" => [],
			],
			'relationship' => [
				"\ElggxUserpointsEvents::elggx_userpoints_relationship" => [],
			],
			'group' => [
				"\ElggxUserpointsEvents::elggx_userpoints_group" => [],
			],
		],
		'profileupdate' => [
			'user' => [
				"\ElggxUserpointsEvents::elggx_userpoints_profile" => [],
			],
		],
		'profileiconupdate' => [
			'user' => [
				"\ElggxUserpointsEvents::elggx_userpoints_profileiconupdate" => [],
			],
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
	'view_extensions' => [
		// Extend CSS/js
		'css/elgg' => [
			'elggx_userpoints/site.css' => [],
		],
		'css/admin' => [
			'elggx_userpoints/admin.css' => [],
		],
		'icon/user/default' => [
			'elggx_userpoints/icon' => [],
		],
		// Extend form with points settings
		'forms/elggx_userpoints/actions' => [
			'elggx_userpoints/actions/general' => ['priority' => 100],
			'elggx_userpoints/actions/profile' => [],
			'elggx_userpoints/actions/poll' => [],
			'elggx_userpoints/actions/izap_videos' => [],
			'elggx_userpoints/actions/tidypics' => [],
			'elggx_userpoints/actions/elggx_fivestar' => [],
			'elggx_userpoints/actions/login' => [],
			'elggx_userpoints/actions/invite_friends' => [],
		],
	],
];
