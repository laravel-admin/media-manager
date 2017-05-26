<?php

namespace LaravelAdmin\MediaManager\Controllers;

use Illuminate\Http\Request;

use LaravelAdmin\MediaManager\Models\Media;
use LaravelAdmin\MediaManager\Upload;

use LaravelAdmin\Crud\Controllers\ResourceController;

class CrudController extends ResourceController
{
    protected $model = Media::class;

    protected $singular_name = 'media';
    protected $plural_name = 'media';

    protected $list_order_by = 'name';

    public function store(Request $request)
    {
        //	Delete files
        if ($request->has('items')) {
            $trigger = $this->model('deleteMultiple', $request->items);

            return back();
        }

        //	Upload files
        if ($file = Upload::handle($request, 'file')) {
            $this->flash("The file is uploaded");

            return $this->redirect("index");
        }

        return back();
    }


    public function update(Request $request, $id, $redirect=true)
    {
        $model = parent::update($request, $id, false);

        if ($request->file('replace')) {
            Upload::update($media)->handle($request, 'replace');
        }

        $this->flash('The changes has been saved');

        return back();
    }

    protected function getValidationRulesOnUpdate()
    {
        return [
            'name' => 'required',
        ];
    }

    public function getFieldsForList()
    {
        return [
            ['id'=>'name', 'label'=>'Name'],
            ['id'=>'size', 'label'=>'Size', 'formatter'=>'sizeFormatted'],
            ['id'=>'type', 'label'=>'Type'],
            ['id'=>'created_at', 'label'=>'Created', 'formatter'=>function ($model) {
                return $model->created_at->format('Y-m-d');
            }],
        ];
    }

    protected function getFieldsForEdit()
    {
        return [
            [
                'id'        =>    'name',
                'label'        =>    'Name',
                'field'        =>    'text',
            ],

            [
                'id'        =>    'replace',
                'label'        =>    'Replace source file',
                'field'        =>    'file',
            ]
        ];
    }


    protected function getFieldsForCreate()
    {
        return [
            [
                'id'        =>    'file',
                'label'        =>    'Upload file',
                'driver'    =>    'file',
            ]
        ];
    }
}
