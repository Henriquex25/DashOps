<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OwnerServerScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $ownerId = auth()->id();
        
        $builder->whereRelation('project', 'owner_id', $ownerId);
    }
}
