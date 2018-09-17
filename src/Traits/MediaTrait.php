<?php

namespace LaravelAdmin\MediaManager\Traits;

trait MediaTrait
{
    public function media()
    {
        return $this->belongsTo(\LaravelAdmin\MediaManager\Models\Media::class);
    }

    public function mediaUrl($field="media")
    {
        if (!method_exists($this, $field)) {
            return '';
        }

        if (!$relation = $this->$field) {
            return '';
        }

        return $relation->url;
    }

    public function imagestyle($style, $field="media")
    {
        if (!method_exists($this, $field)) {
            return '';
        }

        if (!$relation = $this->$field) {
            return '';
        }

        return $relation->imagestyleUrl($style);
    }
}
