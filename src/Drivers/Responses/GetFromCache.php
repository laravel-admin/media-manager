<?php

namespace LaravelAdmin\MediaManager\Drivers\Responses;

use LaravelAdmin\MediaManager\Imagestyle;
use LaravelAdmin\MediaManager\Helpers;
use LaravelAdmin\MediaManager\Contracts\ImagestyleAction;
use Storage;
use Cache;

class GetFromCache implements ImagestyleAction
{
    protected $config;
    protected $style;

    public function __construct(Imagestyle $style, array $config=[])
    {
        $this->style = $style;
        $this->config = $config;
    }

    public function handle()
    {
        if (Cache::has($this->style->getCacheKey())) {
            $data = Cache::get($this->style->getCacheKey());

            return response($data, 200)
                    ->header('Content-Type', $this->style->model->type);
        }
    }
}
