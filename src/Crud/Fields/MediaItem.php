<?php

namespace LaravelAdmin\MediaManager\Crud\Fields;

use LaravelAdmin\Crud\Fields\Driver;

/**
 * Driver for a normal text field
 */
class MediaItem extends Driver
{
	protected $view_path = "media-manager::backend.fields.media-item";

	//	Inherits all methods from the parent driver class
}
