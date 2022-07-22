<?php

namespace App\Filters;

class ProblemFilter
{
    function __invoke($query, $value)
    {
        return $query->where('problem_id', $value);
    }
}