<?php

namespace Marcoboom\MediaManager;

use Marcoboom\MediaManager\Models\Media;
use Storage;
use Intervention\Image\ImageManagerStatic as ImageMaker;

class Imagestyle
{
	public $style;
	public $model;
	public $img;

	public function __construct(Media $model, $style)
	{
		$this->style = $this->getStyle($style);
		$this->model = $model;
	}

	/**
	 * getStyle function.
	 *
	 * @access protected
	 * @param mixed $name
	 * @return void
	 */
	protected function getStyle($name)
	{
		if (!config('media.imagestyles.'.$name))
		{
			throw new \BadMethodCallException('Imagestyle ('.$name.') not found');
		}

		return config('media.imagestyles.'.$name);
	}

	public function handle()
	{
		$data = Storage::disk($this->model->storage)->get($this->model->source);
		$this->img = ImageMaker::make($data);

		foreach ($this->style['actions'] as $class=>$options)
		{
			$action = new $class($this, $options);

			//	If the action has a return value, return this to the controller
			if ($response = $action->handle()) return $response;
		}

		$response = $this->img->response();

		return $response;
	}

	public function getCacheKey()
	{
		$parts = ['media', $this->model->id, $this->style['id'], strtotime($this->model->updated_at)];

		return str_slug(implode(" ", $parts));
	}

	public function getPath()
	{
		$parts = [
			trim(config('media.path.styles'), '/'),
			$this->style['id'],
			trim($this->model->source, '/')
		];

		return implode("/", $parts);
	}

}
