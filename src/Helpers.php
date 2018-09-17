<?php

namespace LaravelAdmin\MediaManager;

class Helpers
{
    public static function cleanFilename($filename)
    {
        $filename = preg_replace('/[^\da-z\-_\.]/i', '-', $filename);
        $filename = strtolower($filename);
        $filename = trim($filename, '-');
        $filename = preg_replace('/-{2,}/', '-', $filename);

        return $filename;
    }

    public static function parsePath($path)
    {
        $parts = collect(explode("/", $path));

        $parts = $parts->map(function ($item) {
            if (strpos($item, "%") !== false) {
                return date(substr($item, 1));
            }

            return $part;
        });

        return implode("/", $parts->all());
    }
}
