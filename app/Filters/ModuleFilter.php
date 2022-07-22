<?php

namespace App\Filters;

class ModuleFilter
{
    function __invoke($query, $value)
    {
        return $query->where('module_id', $value);
    }
}