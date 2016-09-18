<?php

namespace Marcoboom\MediaManager\Contracts;

use Illuminate\Http\Request;

interface UploadDriver
{
	public function handle(Request $request, $reference);
}
