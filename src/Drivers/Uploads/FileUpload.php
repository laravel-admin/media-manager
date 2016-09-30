<?php

namespace LaravelAdmin\MediaManager\Drivers\Uploads;

use Illuminate\Http\Request;
use LaravelAdmin\MediaManager\Contracts\UploadDriver;
use LaravelAdmin\MediaManager\Helpers;
use LaravelAdmin\MediaManager\Upload;

class FileUpload implements UploadDriver {

	/**
	 * The uploader instance who called this object
	 * @var Upload
	 */
	protected $uploader;

	/**
	 * Initialize the object from the given uploader
	 * @param Upload $uploader
	 */
	public function __construct(Upload $uploader)
	{
		$this->uploader = $uploader;
	}

	/**
	 * Main method for this class to handle the request
	 * @param  Request $request
	 * @param  string  $reference (the name of the uploaded file)
	 * @return array | null
	 */
	public function handle(Request $request, $reference)
	{
		//	Check if the uploaded file is correct
		if (!$request->hasFile($reference)) return null;
		if (!$request->file($reference)->isValid()) return null;

		//	Upload the file into the storage and return the path
		$path = $request->file($reference)->store($this->uploader->getPath(), $this->uploader->getStorage());

		//	Create an array with data about the uploaded file
		$model_data = [
				'name'		=>	Helpers::cleanFilename($request->file($reference)->getClientOriginalName()),
				'type'		=>	$request->file($reference)->getMimeType(),
				'size'		=>	$request->file($reference)->getClientSize(),
				'source'	=>	$path,
		];

		return $model_data;
	}

}
