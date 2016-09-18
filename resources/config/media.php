<?php

return [

	'path'	=>	[
		'root'		=>	'uploads',
		'styles'	=>	'styles',
		'format'	=>	'%Y/%m/%d',
	],

	'upload'		=>	[

		'default_driver'	=>	'fileupload',

		'drivers'			=>	[
				'fileupload' 	=>	Marcoboom\MediaManager\Drivers\Uploads\FileUpload::class,
				'url'			=>	Marcoboom\MediaManager\Drivers\Uploads\Url::class,
		],
	],

	'routes'		=>	[
		'frontend'	=>	[
			'controller'	=>	Marcoboom\MediaManager\Controllers\FrontendController::class,
			'name'			=>	'media-manager.frontend.',
			'options'		=>	[],
		],

		'backend'	=>	[
			'controller'	=>	Marcoboom\MediaManager\Controllers\BackendController::class,
			'name'			=>	'media-manager.backend.',
			'options'		=>	[
				'middleware'	=>	['auth'],
				'prefix'		=>	'admin',
			],
		],

		'ajax'		=>	[
			'controller'	=>	Marcoboom\MediaManager\Controllers\AjaxController::class,
			'name'			=>	'media-manager.ajax.',
			'options'		=>	[
				'middleware'	=>	['auth'],
				'prefix'		=>	'admin',
			],
		],
	],

	'imagestyles'	=>	[

		'thumbnail'	=>	[
			'id'	=>	'thumbnail',
			'name'	=>	'Thumbnail (100x100)',
			'actions' => [
				Marcoboom\MediaManager\Drivers\Responses\GetFromCache::class => [],
				Marcoboom\MediaManager\Drivers\Effects\Crop::class => ['width'=>100, 'height'=>100],
				Marcoboom\MediaManager\Drivers\Responses\SaveToCache::class => [],
			]
		],

		'header'	=>	[
			'id'	=>	'header',
			'name'	=>	'Header (1400x500)',
			'actions'	=>	[
				Marcoboom\MediaManager\Drivers\Effects\Fit::class => ['width'=>1400, 'height'=>500],
				Marcoboom\MediaManager\Drivers\Responses\SaveToStorage::class => [],
			]
		],
	],

	'views'		=>	[
			'layout'	=>	'layouts.app',
			'section'	=>	'content',
	],

];
