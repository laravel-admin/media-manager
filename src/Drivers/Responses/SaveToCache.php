<?php

namespace LaravelAdmin\MediaManager\Drivers\Responses;

use LaravelAdmin\MediaManager\Imagestyle;
use LaravelAdmin\MediaManager\Contracts\ImagestyleAction;
use Cache;

class SaveToCache implements ImagestyleAction
{
    protected $config;
    protected $style;

    public function __construct(Imagestyle $style, array $config = [])
    {
        $this->style = $style;
        $this->config = $config;
    }

    public function handle()
    {
        Cache::put($this->style->getCacheKey(), (string)$this->style->img->encode(), 10000);
    }
}
