<?php

namespace App\Filters;

use App\Filters\QueryFilter;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class TagFilter extends QueryFilter
{
    public function sort($sort)
    {
        switch ($sort) {
            case 'new':
                return $this->builder->orderByDesc('created_at');
            case 'old':
                return $this->builder->orderBy('created_at');
            case 'name':
                return $this->builder->orderBy('title');
        }
    }

    public function search($search)
    {
        return $this->builder->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }
}
