<?php

namespace LaravelAdmin\MediaManager\Drivers\Effects;

use LaravelAdmin\MediaManager\Imagestyle;
use LaravelAdmin\MediaManager\Contracts\ImagestyleAction;

class Resize implements ImagestyleAction
{
    public function __construct(protected Imagestyle $style, protected array $config = [])
    {
    }

    public function handle()
    {
        $this->style->img->resize($this->config['width'], $this->config['height'], function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
