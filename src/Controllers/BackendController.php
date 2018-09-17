<?php

namespace LaravelAdmin\MediaManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use LaravelAdmin\MediaManager\Models\Media;
use LaravelAdmin\MediaManager\Upload;

class BackendController extends Controller
{
    public function __construct()
    {
        //view()->share('search_url', route('media-manager.backend.index'));
    }

    public function index(Request $request)
    {
        $builder = Media::orderBy('created_at', 'desc');

        if ($request->get('s')) {
            $builder->where('name', 'like', '%'.$request->get('s').'%');
        }

        $media = $builder->paginate(10);

        return view('media-manager::backend.index', compact('media'));
    }

    public function create()
    {
        return view('media-manager::backend.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */

    public function store(Request $request)
    {
        //	Delete files
        if ($request->has('items')) {
            $trigger = Media::deleteMultiple($request->items);

            return back();
        }

        //	Upload files
        if ($file = Upload::handle($request, 'file')) {
            return redirect()->route(config('media.routes.backend.name').'index');
        }

        return back();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        return $this->edit($request, $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $media 	= 	Media::findOrFail($id);

        return view('media-manager::backend.edit', compact('media'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, ['name'=>'required']);

        $media 	= 	Media::findOrFail($id);

        $media->update(['name'=>$request->name]);

        if ($request->file('replace')) {
            if (Upload::update($media)->handle($request, 'replace')) {
                return back();
            }

            return back();
        }

        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $media->delete();

        return redirect()->route(config('media.routes.backend.name').'index');
    }
}
