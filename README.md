# Laravel-Media-Manager
This package has 4 main goals:
* Provide an Eloquent model around your applications storage
* By implementing a trait on your models automaticly create a 1-n relation with the media model
* Create image styles for images and store or cache these
* Provide a Vue component to show and browse for media


> Note: This package is still under development. Using it on productions environments is on your own risk. Collaborations and tips are always welcome

## Requirements
This package is completely build on the philosofy of Laravel 5.3. It uses the 5.3 method to quickly upload files and a Vue components setup as in Laravel Elixir 6. Do not use this package for Laravel 5.2 or lower. The templates of the Vue components are completly build on Bootstrap 3.

## Installation

Require this package with Composer
    
    composer require marcoboom/laravel-media-manager
    
Add the ServiceProvider to the providers array in app/config/app.php

    Marcoboom\MediaManager\MediaManagerProvider::class

If you want to edit the config or backend views you need to publish these resources to your application

    php artisan vendor:publish
    
The package generates a migration file for the media model, so migrate your database to create the table.

    php artisan migrate

To use the Vue components in your (admin) applications, require the javascript bootstrap file from your vendor folder in your app.js file.

    require('../../../vendor/marcoboom/laravel-media-manager/resources/js/bootstrap.js');
    
> Note: The javascript requires Dropzone to work, use the following statement to install it:

    npm install dropzone --save-dev

## Upload media

With the following code you can upload a file to your default storage and returns the created media model.

```php
use Marcoboom\MediaManager\Upload;

$media = Upload::handle($request, 'file');

```

> Note: the first parameter of the handle method is the default Laravel Request object. The second parameter is the name of the submitted file.

It's possible to upload a file to another storage. This storage has to be a configured storage as in config/filesystems.php

```php
$media = Upload::storage('s3')->handle($request, 'file');
```

> Note: The Eloquent model saves the storage in the database, so your application always knows where to find the uploaded file.

The Uploader uses a driver based system to add files to your storage and database. You can create your own drivers and add it to the media config, within upload/drivers. Your driver has to implement the following interface:

    Marcoboom\Mediamanager\Contracts\UploadDriver
    
By default the package provides two drivers. The default for uploading files from your request and a second one 'url' to directly save files from an url. To change the driver, just do:

```php
$media = Upload::with('url')->handle($request, 'http://domain.com/somefile.jpg');
```

You can also replace an existing model instead of create a new one. 

```php
$media = Upload::update($current_model)->handle($request, 'file');
```

##  Create a relation

If you want to create a relation between your model and the media model, you can do this simple to add a field to your table and add the MediaTrait to your model.

Create a migration and add the following:

```php
$table->integer('media_id')->unsigned()->nullable();
$table->foreign('media_id')->references('id')->on('media')->onDelete('set null');	
```

> Note: add ->after('fieldname') to add the field at the position you want.

Now add the MediaTrait to your model

```php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Marcoboom\MediaManager\Traits\MediaTrait;

class Blog extends Model
{
	use MediaTrait;
```

The trait is based on the convention that the field you add in your database is called 'media_id'. If you prefer another naming, or you have more than one field like a header, just add a relation manually to your model:

```php
public function myfieldname()
{
	return $this->belongsTo(\Marcoboom\MediaManager\Models\Media::class);
}
```





