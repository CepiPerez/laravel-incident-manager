<?php

namespace App\Filters;

class OrderByFilter
{
    function __invoke($query, $value)
    {
        if ($value)
		{
			list($order, $sort) = explode('__', $value);

            if ($order=='client_id')
                return $query->orderBy('client_id', $sort)->orderBy('title', $sort); 

            else
    			return $query->orderBy($order, $sort);
		}
		else
		{
			return $query->orderBy('id', 'desc');
		}
    }
}