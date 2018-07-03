<?php

return [
    'path' => [
        'root' => 'uploads',
        'styles' => 'styles',
        'format' => '%Y/%m/%d',
    ],

    'upload' => [
        'default_driver' => 'fileupload',

        'max_filesize' => 6000,

        'allowed_filetypes' => null,

        'drivers' => [
                'fileupload' => LaravelAdmin\MediaManager\Drivers\Uploads\FileUpload::class,
                'url' => LaravelAdmin\MediaManager\Drivers\Uploads\Url::class,
        ],
    ],

    'routes' => [
        'frontend' => [
            'controller' => LaravelAdmin\MediaManager\Controllers\FrontendController::class,
            'name' => 'media-manager.frontend.',
            'options' => [],
        ],

        'backend' => [
            'controller' => LaravelAdmin\MediaManager\Controllers\BackendController::class,
            'name' => 'media-manager.backend.',
            'options' => [
                'middleware' => ['auth'],
                'prefix' => 'admin',
            ],
        ],

        'ajax' => [
            'controller' => LaravelAdmin\MediaManager\Controllers\AjaxController::class,
            'name' => 'media-manager.ajax.',
            'options' => [
                'middleware' => ['auth'],
                'prefix' => 'admin',
            ],
        ],
    ],

    'imagestyles' => [
        'thumbnail' => [
            'id' => 'thumbnail',
            'name' => 'Thumbnail (100x100)',
            'actions' => [
                LaravelAdmin\MediaManager\Drivers\Responses\GetFromCache::class => [],
                LaravelAdmin\MediaManager\Drivers\Effects\Crop::class => ['width' => 100, 'height' => 100],
                LaravelAdmin\MediaManager\Drivers\Responses\SaveToCache::class => [],
            ]
        ],

        'header' => [
            'id' => 'header',
            'name' => 'Header (1400x500)',
            'actions' => [
                LaravelAdmin\MediaManager\Drivers\Effects\Fit::class => ['width' => 1400, 'height' => 500],
                LaravelAdmin\MediaManager\Drivers\Responses\SaveToStorage::class => [],
            ]
        ],
    ],

    'views' => [
            'layout' => 'layouts.app',
            'section' => 'content',
    ],
];
