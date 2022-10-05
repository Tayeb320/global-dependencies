<?php

namespace Tayeb320\GlobalServiceDependencies\Traits;

use Tayeb320\GlobalServiceDependencies\GlobalConstant;

trait ModelStatusScopeHandler
{
    public function scopeActive($query)
    {
        return $query->where('status', GlobalConstant::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', GlobalConstant::STATUS_INACTIVE);
    }
}
