<?php

namespace LaravelAdmin\MediaManager\Drivers\Effects;

use LaravelAdmin\MediaManager\Imagestyle;
use LaravelAdmin\MediaManager\Contracts\ImagestyleAction;

class ResizeCanvas implements ImagestyleAction
{
    public function __construct(protected Imagestyle $style, protected array $config = [])
    {
    }

    public function handle()
    {
        $this->style->img->resizeCanvas($this->config['width'], $this->config['height']);
    }
}
