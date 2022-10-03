<?php

namespace Itclan\GlobalServiceDependencies\Providers;

use Itclan\GlobalServiceDependencies\GlobalConstant;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
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

    public function delete($id)
    {
        try {
            return $this->model::findOrfail($id)->delete();
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
