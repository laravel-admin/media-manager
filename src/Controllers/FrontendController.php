<?php

namespace LaravelAdmin\MediaManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelAdmin\MediaManager\Models\Media;
use LaravelAdmin\MediaManager\Imagestyle;
use Storage;

class FrontendController extends Controller
{
    public function __construct()
    {
        $this->middleware(config('media.routes.frontend.middleware'));
    }

    /**
     * Get the file based on the media object
     * @param  Request $request
     * @param  int  $id The id of the media object
     * @param  string  $slug    Optionally send the name of the file
     * @return Response
     */
    public function file(Request $request, $id, $slug = '')
    {
        $media = Media::findOrFail($id);

        if ($data = Storage::disk($media->storage)->get($media->source)) {
            return response($data, 200)
                    ->header('Content-Type', $media->type);
        }

        abort(404);
    }

    /**
     * Parse a media object an imagestyle
     * @param  Request 	$request    [description]
     * @param  string  	$imagestyle The imagestyle defined in the media config
     * @param  int  	$id         The id of the media object
     * @param  string  $slug       Optionally send the name of the file in the url
     * @return Response
     */
    public function img(Request $request, $imagestyle, $id, $slug = '')
    {
        $media = Media::findOrFail($id);

        $image = new Imagestyle($media, $imagestyle);

        return $image->handle();
    }
}
