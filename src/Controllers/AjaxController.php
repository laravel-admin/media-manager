<?php

namespace LaravelAdmin\MediaManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelAdmin\MediaManager\Models\Media;
use LaravelAdmin\MediaManager\Upload;

class AjaxController extends Controller
{
    /**
     * List all media from the database, paginated by 8
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $builder = Media::orderBy('created_at', 'desc');

        if ($request->has('type')) {
            $builder->where('name', 'like', '%' . explode('/', $request->type)[1]);
        }

        if ($request->has('s')) {
            $builder->where('name', 'like', '%' . $request->s . '%');
        }

        return $builder->paginate(12);
    }

    /**
     * Upload a file from a POST request
     * @param  Request $request
     * @return Response | null
     */
    public function store(Request $request)
    {
        $check = 'required|max:' . ((!config('media.upload.max_filesize')) ? 4000 : config('media.upload.max_filesize'));
        if (config('media.upload.allowed_filetypes')) {
            $check = $check . '|mimes:' . config('media.upload.allowed_filetypes');
        }

        $this->validate($request, [
            'file' => $check
        ]);

        if ($media = Upload::handle($request, 'file')) {
            return $media;
        }

        return null;
    }

    public function show($id)
    {
        return Media::findOrFail($id);
    }
}
