<?php

namespace App\Filters;

class AreaFilter
{
    function __invoke($query, $value)
    {
        return $query->where('area_id', $value);
    }
}