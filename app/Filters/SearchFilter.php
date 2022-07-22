<?php

namespace App\Filters;

class SearchFilter
{
    function __invoke($query, $value)
    {
        return $query->where( function($query) use ($value) {
            return $query->where('incidents.title', 'LIKE', '%'.$value.'%')
                    ->orWhere('incidents.description', 'LIKE', '%'.$value.'%')
                   ->orWhere('incidents.id', $value);
        });
    }
}