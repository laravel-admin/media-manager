<?php

namespace LaravelAdmin\MediaManager;

use Illuminate\Http\Request;
use LaravelAdmin\MediaManager\Models\Media;
use Storage;

class Upload
{
    /**
     * Basepath to upload within the storage
     * @var string
     */
    protected $path;

    /**
     * The chosen upload driver, default is defined in the config
     * @var string
     */
    protected $driver;

    /**
     * The chosen storage type compatible with the available storages of Laravel
     * @var string
     */
    protected $storage;

    /**
     * Existing media model to overwrite with an upload
     * @var Media | null
     */
    protected $model;

    /**
     * Will the uploaded media object directly active?
     * @var boolean
     */
    protected $active = false;

    /**
     * Initialize the upload object and fill the attributes with the config
     */
    public function __construct()
    {
        $this->storage = config('filesystems.default');

        $this->driver = config('media.upload.default_driver');
    }

    /**
     * Select the driver to perform the upload
     * @param  string $driver
     * @return this
     */
    protected function _with($driver)
    {
        //	IS the driver defined in the config?
        if (!config('media.upload.drivers.' . $driver)) {
            throw new \BadArgumentException('Driver ' . $driver . ' does not exists');
        }

        //	Set the driver
        $this->driver = $driver;

        return $this;
    }

    /**
     * Handle the upload to load the chosen driver
     * and save the data into the model
     * @param  Request $request
     * @param  string $reference
     * @return Media | null
     */
    protected function _handle(Request $request, $reference)
    {
        //	Get the classname of the driver
        $class = config('media.upload.drivers.' . $this->driver);
        $driver = new $class($this);

        try {
            //	Call the handle function on the driver
            if ($data = $driver->handle($request, $reference)) {
                //	Save the data to the model
                return $this->save($data);
            }
            return null;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Save the data to the model
     * @param  array  $data
     * @return Media
     */
    public function _save(array $data)
    {
        //	Define the data to put into the model
        $vars = [
            'active' => $this->active,
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'name' => $data['name'],
            'type' => $data['type'],
            'size' => $data['size'],
            'source' => $data['source'],
            'storage' => $this->storage,
            'styles' => null,
        ];

        //	Passed through an existing model to overwrite
        if ($this->model) {
            //	First remove the file connected to the current model
            $this->model->deleteInStorage();

            //	Update the model with new data
            $this->model->update($vars);
        } else {
            //	Create a new model
            $this->model = Media::create($vars);
        }

        return $this->model;
    }

    /**
     * Select the storage system, defined in config/filesystems
     * @param  string $storage
     * @return this
     */
    public function _storage($storage)
    {
        //	Does the filesystem exists?
        if (!config('filesystems.disks.' . $storage)) {
            throw new \InvalidArgumentException('Selected storage ' . $storage . ' not found');
        }

        $this->storage = $storage;

        return $this;
    }

    /**
     * Mark the creating model as active (optional)
     * @return this
     */
    public function _activate()
    {
        $this->active = true;

        return $this;
    }

    /**
     * Receive a model to update instead of creating a new one
     * @param  Media  $model
     * @return this
     */
    public function _update(Media $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Create a custom path to save the upload
     * @param  string $path
     * @return this
     */
    public function _path($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the current storage
     * @return string
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Get the current driver
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Get the default path defined in the config
     * @return string
     */
    public function getPath()
    {
        if ($this->path) {
            return $this->path;
        }

        $parts = [
            trim(config('media.path.root'), '/'),
            Helpers::parsePath(trim(config('media.path.format'), '/'))
        ];

        return implode('/', $parts);
    }

    /**
     * Provide a method to call all protected methods
     * starting with an underscore call staticly.
     * @return self | null
     */
    public static function __callStatic($name, $arguments)
    {
        $self = new static();
        $name = '_' . $name;

        if (method_exists($self, $name)) {
            return call_user_func_array([$self, $name], $arguments);
        }

        return null;
    }

    /**
     * Provide a method to make all methods public available
     * starting with an underscore
     * @return self | null
     */
    public function __call($name, $arguments)
    {
        $name = '_' . $name;

        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }

        return null;
    }
}
