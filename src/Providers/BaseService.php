<?php

namespace Itclanbd\GlobalServiceDependencies\Providers;

use Itclanbd\GlobalServiceDependencies\GlobalConstant;
use Illuminate\Database\Eloquent\Model;
use Itclanbd\GlobalServiceDependencies\Providers\Utils\FileUploadService;

class BaseService
{
    protected $model;

    public function __construct(Model $model, FileUploadService $fileUploadService)
    {
        $this->model = $model;
        $this->fileUploadService = $fileUploadService;
    }

    public function getByUserId($id, $with = [])
    {
        try {
            return $this->model::with($with)->where('user_id', $id)->first();

        } catch (\Exception $e) {
            $this->logErrorResponse($e);
        }
    }

    public function get($id = null, $with = [])
    {
        try {
            if ($id) {
                return $this->model::with($with)->findOrFail($id);
            } else {
                return $this->model::with($with)->get();
            }
        } catch (\Exception $e) {
            $this->logErrorResponse($e);
        }
    }

    public function getActiveData($id = null, $with = [])
    {
        try {
            if ($id) {
                return $this->model::with($with)->active()->findOrFail($id);
            } else {
                return $this->model::with($with)->active()->get();
            }
        } catch (\Exception $e) {
            $this->logErrorResponse($e);
        }
    }

    public function storeOrUpdate($data, $id = null)
    {
        try {
            if ($id) {
                // Update
                return $this->model::findOrFail($id)->update($data);
            } else {
                // Create
                return $this->model::create($data);
            }
        } catch (\Exception $e) {
            $this->logErrorResponse($e);
        }
    }

    public function createOrUpdateWithFile(array $data, $file_field_name, $id = null)
    {
        try {
            if ($id) {
                $object = $this->model->findOrFail($id);
                if (isset($data[$file_field_name]) && $data[$file_field_name] != null) {
                    $data[$file_field_name] = $this->fileUploadService->uploadFile($data[$file_field_name], $object->$file_field_name);
                }
                return $object->update($data);
            } else {
                if (isset($data[$file_field_name]) && $data[$file_field_name] != null) {
                    $data[$file_field_name] = $this->fileUploadService->uploadFile($data[$file_field_name]);
                }
                return $this->model::create($data);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($id)
    {
        try {
            return $this->model::findOrfail($id)->delete();
        } catch (\Exception $e) {
            $this->logErrorResponse($e);
        }
    }

    public function deleteWithFile($id)
    {
        try {
            $object = $this->model->findOrFail($id);
            $this->fileUploadService->delete($this->model::FILE_STORE_PATH . '/' . $object->file);
            return $object->delete();
        } catch (\Exception $e) {
            $this->logErrorResponse($e);
        }
    }

    public function logErrorResponse($e)
    {
        throw $e;
    }

    //get paginated data with status
    public function paginateWithStatus($query, $status = GlobalConstant::STATUS_ACTIVE)
    {
        //get default pagination from config if not provided
        $limit = request('per_page',ic_config('default_paginate'));

        //match works like switch case
        $query = match($status) {
            GlobalConstant::STATUS_ACTIVE => $query->active(),
            GlobalConstant::STATUS_INACTIVE => $query->inactive(),
            default => $query,
        };

        return $query->paginate($limit);
    }

}
