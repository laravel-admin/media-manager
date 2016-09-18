# Laravel-Media-Manager
This package has 4 main goals:
* Provide an Eloquent model around your applications storage
* By implementing a trait on your models automaticly create a 1-n relation with the media model
* Create image styles for images and store or cache these
* Provide a Vue component to show and browse for media


> Note: This package is still under development. Using it on productions environments is on your own risk. Collaborations and tips are always welcome

## Requirements
This package is completely build on the philosofy of Laravel 5.3. It uses the 5.3 method to quickly upload files and a Vue components setup as in Laravel Elixir 6. Do not use this package for Laravel 5.2 or lower.

## Installation

Require this package with Composer
    
    composer require marcoboom/laravel-media-manager
    
Add the ServiceProvider to the providers array in app/config/app.php

    Marcoboom\MediaManager\MediaManagerProvider::class

If you want to edit the config or backend views you need to publish these resources to your application

    php artisan vendor:publish

To use the Vue components in your (admin) applications, require the javascript bootstrap file from your vendor folder in your app.js file.

    require('../../../vendor/marcoboom/laravel-media-manager/resources/js/bootstrap.js');

