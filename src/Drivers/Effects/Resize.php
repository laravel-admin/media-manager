<?php

namespace LaravelAdmin\MediaManager\Drivers\Effects;

use LaravelAdmin\MediaManager\Imagestyle;
use LaravelAdmin\MediaManager\Contracts\ImagestyleAction;

class Resize implements ImagestyleAction
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
        $this->style->img->resize($this->config['width'], $this->config['height'], function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
