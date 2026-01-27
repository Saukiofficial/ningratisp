<?php

namespace App\Services\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModelService
{
    /** Model */
    protected Model $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function get($id)
    {
        return $this->model->findOrFail($id);
    }

    public function listData($n = 10, $p = 1)
    {
        return $this->model->with([])->paginate($n, page: $p);
    }

    public function save(array $data, $id = null)
    {
        if ($id) {
            $this->model = $this->get($id);
        }

        $this->model->fill($data);
        $this->model->save();
        return $this->model;
    }

    public function delete($id, $softdelete = true)
    {
        $model = $this->get($id);
        if ($softdelete) {
            $ok = $model->delete();
        } else {
            $ok = $model->forceDelete();
        }

        return $ok;
    }

    public function getColumnsName()
    {
        return $this->model->getListColumnName();
    }

    public function getModelName()
    {
        return \Illuminate\Support\Str::headline(class_basename($this->model));
    }

    public function getAll($columns = ['*'], $relatiions = [])
    {
        return $this->model->with($relatiions)->get($columns);
    }

    public function getDropdowns($id, $name, $relatiions = [])
    {
        return $this->model->with($relatiions)->pluck($name, $id);
    }

    public function buildData($relations = [])
    {
        return $this->model->with($relations);
    }
}
