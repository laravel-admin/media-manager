<?php

namespace LaravelAdmin\MediaManager\Drivers\Uploads;

use Illuminate\Http\Request;
use LaravelAdmin\MediaManager\Contracts\UploadDriver;
use LaravelAdmin\MediaManager\Helpers;
use LaravelAdmin\MediaManager\Upload;

use Storage;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;

class Url implements UploadDriver {

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
	 * @param  string  $reference (the url of the file)
	 * @return array | null
	 */
	public function handle(Request $request, $reference)
	{
		//	Use Guzzle to fetch the data of the file
		$client = new Guzzle();

		try {
			$res = $client->request('GET', $reference);
		} catch (RequestException $e) {
			throw new \RuntimeException('The url '.$reference.' is not accessible');
		}

		//	Response code has to be lower dan 300 to save the file
		if ($res->getStatusCode() >= 300)
		{
			throw new Exception('The url '.$reference.' is not accessible');
		}

		$data 			= 	(string)$res->getBody();
		$filename		=	$this->getFilenameFromUrl($reference);
		$server_filename= 	md5($data).".".$this->getExtensionFromUrl($reference);
		$path 			=	$this->uploader->getPath().'/'.$server_filename;

		Storage::disk($this->uploader->getStorage())->put($path, $data);

		$model_data = [
				'name'		=>	Helpers::cleanFilename($filename),
				'type'		=>	$res->getHeader('content-type')[0],
				'size'		=>	$res->getHeader('content-length')[0],
				'source'	=>	$path,
		];

		return $model_data;
	}

	/**
	 * Get the single filename from the given url
	 * @param  string $url
	 * @return string
	 */
	protected function getFilenameFromUrl($url)
	{
		$path = parse_url($url, PHP_URL_PATH);
		$parts = explode("/", $path);

		return end($parts);
	}

	/**
	 * Get the extension of the file based on the given url
	 * @param  string $url
	 * @return string
	 */
	protected function getExtensionFromUrl($url)
	{
		$filename = $this->getFilenameFromUrl($url);
		$dot = strripos($filename, ".");

		return substr($filename, $dot+1);
	}
}
