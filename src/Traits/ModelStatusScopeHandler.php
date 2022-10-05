<?php

namespace Itclanbd\GlobalServiceDependencies\Traits;

use Itclanbd\GlobalServiceDependencies\GlobalConstant;

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
