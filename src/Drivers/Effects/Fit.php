<?php

namespace LaravelAdmin\MediaManager\Drivers\Effects;

use LaravelAdmin\MediaManager\Contracts\ImagestyleAction;
use LaravelAdmin\MediaManager\Imagestyle;

class Fit implements ImagestyleAction
{
    public function __construct(protected Imagestyle $style, protected array $config = [])
    {
    }

    public function handle()
    {
        $this->style->img->coverDown($this->config['width'], $this->config['height']);
    }
}
