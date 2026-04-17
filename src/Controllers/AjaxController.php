<?php

namespace LaravelAdmin\MediaManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelAdmin\MediaManager\Models\Media;
use LaravelAdmin\MediaManager\Upload;

class AjaxController extends Controller
{
    /**
     * List all media from the database, paginated
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $builder = Media::orderBy('created_at', 'desc');

        if ($request->has('s') && !empty($request->s)) {
            $builder->where('name', 'like', '%' . $request->s . '%');
            if ($request->has('type') && !empty($request->type)) {
                $types = $request->type;
                $builder->where(function ($query) use ($types) {
                    foreach (explode(',', $types) as $type) {
                        $query->orWhere('type', $type);
                    }
                });
            }
        } elseif ($request->has('type') && !empty($request->type)) {
            foreach (explode(',', $request->type) as $type) {
                $builder->orWhere('type', $type);
            }
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

    /**
     * Delete a media item if it is not linked to any objects
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        $linkedObjects = [];

        if ($types = config('media.types')) {
            foreach ($types as $type) {
                if (!isset($type['fields']) || !class_exists($type['model'])) {
                    continue;
                }

                $query = $type['model']::query();
                $query->where(function ($q) use ($type, $id) {
                    foreach ($type['fields'] as $field) {
                        $q->orWhere($field, $id);
                    }
                });

                $records = $query->get();

                if ($records->count() > 0) {
                    $linkedObjects[] = [
                        'model' => class_basename($type['model']),
                        'count' => $records->count(),
                        'records' => $records->map(function ($r) {
                            return [
                                'id' => $r->id,
                                'name' => $r->name ?? $r->title ?? "#{$r->id}",
                            ];
                        })->values(),
                    ];
                }
            }
        }

        if (!empty($linkedObjects)) {
            return response()->json([
                'message' => 'This image is still in use and cannot be deleted.',
                'linked_objects' => $linkedObjects,
            ], 409);
        }

        $media->delete();

        return response()->json(['message' => 'Media deleted successfully.']);
    }
}
