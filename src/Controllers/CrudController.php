<?php

namespace LaravelAdmin\MediaManager\Controllers;

use Illuminate\Http\Request;
use LaravelAdmin\MediaManager\Models\Media;
use LaravelAdmin\MediaManager\Upload;
use LaravelAdmin\Crud\Controllers\ResourceController as BaseController;

class CrudController extends BaseController
{
    protected $authorizedRoles = ['editor', 'administrator'];

    protected $model = Media::class;

    protected $singular_name = 'media';
    protected $plural_name = 'media';

    protected $list_order_by = 'created_at';
    protected $list_search_on = 'name';

    public function store(Request $request)
    {
        //	Delete files
        if ($request->has('items')) {
            $trigger = $this->model('deleteMultiple', $request->items);

            return back();
        }

        $this->validate($request, $this->getValidationRulesOnStore(), $this->getValidationMessagesOnStore());

        try {
            //	Upload files
            if ($file = Upload::handle($request, 'file')) {
                $this->flash('The file is uploaded');

                return $this->redirect('index');
            }
        } catch (\Exception $ex) {
            // dd($ex);
            $this->flash($ex->getMessage(), 'danger');
        }

        return back();
    }

    public function update(Request $request, $id, $redirect = true)
    {
        $this->validate($request, $this->getValidationRulesOnUpdate(), $this->getValidationMessagesOnUpdate());

        $model = parent::getModelInstance($id); //parent::update($request, $id, false);

        if ($request->file('replace')) {
            Upload::update($model)->handle($request, 'replace');
        }

        $payload = $this->getPayloadOnUpdate($request->all());
        $model->update($payload);

        $this->flash('The changes has been saved');

        return back();
    }

    public function getFieldsForList()
    {
        return [
            ['id' => 'name', 'label' => 'Name'],
            ['id' => 'size', 'label' => 'Size', 'formatter' => 'sizeFormatted'],
            ['id' => 'type', 'label' => 'Type'],
            ['id' => 'created_at', 'label' => 'Created', 'formatter' => function ($model) {
                return $model->created_at->format('d-m-Y');
            }],
        ];
    }

    protected function getFieldsForCreate()
    {
        return [
            [
                'id' => 'file',
                'label' => 'Upload file',
                'field' => 'file',
            ],
        ];
    }

    protected function getFieldsForEdit()
    {
        return [
            [
                'id' => 'name',
                'label' => 'Name',
                'field' => 'text',
            ],

            [
                'id' => 'replace',
                'label' => 'Replace source file',
                'field' => 'file',
            ]
        ];
    }

    protected function getValidationRulesOnStore()
    {
        $check = 'required|max:' . ((!config('media.upload.max_filesize')) ? 4000 : config('media.upload.max_filesize'));
        if (config('media.upload.allowed_filetypes')) {
            $check = $check . '|mimes:' . config('media.upload.allowed_filetypes');
        }

        return [
            'file' => $check
        ];
    }

    protected function getValidationRulesOnUpdate()
    {
        $check = 'max:' . ((!config('media.upload.max_filesize')) ? 4000 : config('media.upload.max_filesize'));
        if (config('media.upload.allowed_filetypes')) {
            $check = $check . '|mimes:' . config('media.upload.allowed_filetypes');
        }

        return [
            'name' => 'required',
            'replace' => $check
        ];
    }
}
