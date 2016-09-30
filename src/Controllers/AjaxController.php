<?php

namespace LaravelAdmin\MediaManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelAdmin\MediaManager\Models\Media;
use LaravelAdmin\MediaManager\Upload;

class AjaxController extends Controller
{
	public function __construct()
	{
		//$this->middleware(config('media.routes.ajax.middleware'));
	}

	/**
	 * List all media from the database, paginated by 8
	 * @param  Request $request
	 * @return Response
	 */
    public function index(Request $request)
	{
		$builder = Media::orderBy('created_at', 'desc');

		if ($request->has('s')) $builder->where('name','like','%'.$request->s.'%');

		return $builder->paginate(8);
	}

	/**
	 * Upload a file from a POST request
	 * @param  Request $request
	 * @return Response | null
	 */

	public function store(Request $request)
	{
		if ($media = Upload::handle($request, 'file'))
		{
			return $media;
		}

		return null;
	}
}
