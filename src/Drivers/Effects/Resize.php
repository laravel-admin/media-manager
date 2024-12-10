<?php

namespace LaravelAdmin\MediaManager\Drivers\Effects;

use LaravelAdmin\MediaManager\Contracts\ImagestyleAction;
use LaravelAdmin\MediaManager\Imagestyle;

class Resize implements ImagestyleAction
{
    public function __construct(protected Imagestyle $style, protected array $config = [])
    {
    }

    public function handle()
    {
        if (!is_null($this->config['width']) && !is_null($this->config['height'])) {
            $this->style->img->scaleDown($this->config['width'], $this->config['height']);
        } elseif (!is_null($this->config['width'])) {
            $this->style->img->scaleDown(width: $this->config['width']);
        } elseif (!is_null($this->config['height'])) {
            $this->style->img->scaleDown(height: $this->config['height']);
        }
    }
}
