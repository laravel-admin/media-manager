<?php

namespace LaravelAdmin\MediaManager;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use LaravelAdmin\MediaManager\Models\Media;

class Imagestyle
{
    public $style;
    public ImageInterface $img;

    public function __construct(public Media $model, $style)
    {
        $this->style = $this->getStyle($style);
    }

    /**
     * getStyle function.
     *
     * @param mixed $name
     *
     * @return void
     */
    protected function getStyle($name)
    {
        if (!config('media.imagestyles.' . $name)) {
            throw new \BadMethodCallException('Imagestyle (' . $name . ') not found');
        }

        return config('media.imagestyles.' . $name);
    }

    public function handle()
    {
        if ($this->model->styles && !empty($this->model->styles[$this->style['id']]) && $img = Storage::disk($this->model->storage)->url($this->getPath())) {
            return redirect($img);
        }

        try {
            $data = Storage::disk($this->model->storage)->get($this->model->source);
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            return null;
        }
        $imageManager = ImageManager::gd();
        $this->img = $imageManager->read($data);

        foreach ($this->style['actions'] as $class => $options) {
            $action = new $class($this, $options);

            //	If the action has a return value, return this to the controller
            if ($response = $action->handle()) {
                return $response;
            }
        }

        return response()->stream(function () {
            echo $this->img->encode();
        }, 200, ['Content-Type' => $this->model->type]);
    }

    public function getCacheKey()
    {
        $parts = ['media', $this->model->id, $this->style['id'], strtotime($this->model->updated_at)];

        return Str::slug(implode(' ', $parts));
    }

    public function getPath()
    {
        $parts = [
            trim(config('media.path.styles'), '/'),
            $this->style['id'],
            trim($this->model->source, '/')
        ];

        return implode('/', $parts);
    }
}
