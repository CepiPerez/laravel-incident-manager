<?php

namespace App\Filters;

class GroupFilter
{
    function __invoke($query, $value)
    {
        return $query->where('group_id', $value);
    }
}