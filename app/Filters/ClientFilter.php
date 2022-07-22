<?php

namespace App\Filters;

class ClientFilter
{
    function __invoke($query, $value)
    {
        return $query->where('client_id', $value);
    }
}