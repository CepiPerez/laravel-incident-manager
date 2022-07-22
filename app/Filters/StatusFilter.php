<?php

namespace App\Filters;

class StatusFilter
{
    function __invoke($query, $value)
    {
        if ($value=='opened')
            return $query->where('status_id', '<', 10);

        elseif ($value=='ended')
            return $query->whereIn('status_id', [10, 20]);

        else
            return $query->where('status_id', $value);
    }
}