<?php

namespace LaravelAdmin\MediaManager\Drivers\Responses;

use LaravelAdmin\MediaManager\Imagestyle;
use LaravelAdmin\MediaManager\Contracts\ImagestyleAction;
use Storage;

class SaveToStorage implements ImagestyleAction
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
        if (Storage::disk($this->style->model->storage)->put($this->style->getPath(), (string)$this->style->img->encode())) {
            $current_styles = $this->style->model->styles ?: [];
            $current_styles[$this->style->style['id']] = strtotime('now');
            $this->style->model->styles = $current_styles;
            //$this->style->model->save(['timestamps' => false]); //--> does not work for disabling timestamps
            $this->style->model->timestamps = false;
            $this->style->model->save();
        }
    }
}
