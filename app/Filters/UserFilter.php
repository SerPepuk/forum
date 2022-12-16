<?php

namespace App\Filters;

use App\Filters\QueryFilter;

class UserFilter extends QueryFilter
{
    public function search($search = '')
    {
        return $this->builder->where('name', 'like', '%' . $search . '%');
    }
}
