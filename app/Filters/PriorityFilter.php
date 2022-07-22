<?php

namespace App\Filters;

class PriorityFilter
{
    function __invoke($query, $value)
    {
        return $query->where('priorities.id', $value);
    }
}