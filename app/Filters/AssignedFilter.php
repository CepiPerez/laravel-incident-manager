<?php

namespace App\Filters;

class AssignedFilter
{
    function __invoke($query, $value)
    {
        return $query->where('assigned', $value);
    }
}