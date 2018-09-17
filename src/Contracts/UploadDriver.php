<?php

namespace LaravelAdmin\MediaManager\Contracts;

use Illuminate\Http\Request;

interface UploadDriver
{
    public function handle(Request $request, $reference);
}
