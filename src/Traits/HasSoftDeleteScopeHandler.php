<?php

namespace Itclanbd\GlobalServiceDependencies\Traits;

trait HasSoftDeleteScopeHandler
{
    public function scopeHasSoftDelete()
    {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this));
    }
}
