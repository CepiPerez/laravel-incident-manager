<?php

namespace App\Filters;

class IncidentFilters
{
    protected $filters = [
        'assigned'   => AssignedFilter::class,
        'client_id'  => ClientFilter::class,
        'group_id'   => GroupFilter::class,
        'problem_id' => ProblemFilter::class, 
        'module_id'  => ModuleFilter::class,
        'area_id'    => AreaFilter::class,
        'pid'        => PriorityFilter::class,
        'status_id'  => StatusFilter::class,
        'user'       => UserPermissionsFilter::class,
        'order'      => OrderByFilter::class,
        'search'     => SearchFilter::class
    ];

    public function apply($query, $query_filters)
    {
        $keys = array_keys($this->filters);

        foreach ($query_filters as $name => $value)
        {
            if (in_array($name, $keys) && $value!=null)
            {
                $filterInstance = new $this->filters[$name];
                $query = $filterInstance($query, $value);
            }
        }

        $filterInstance = new $this->filters['user'];
        $query = $filterInstance($query, Auth()->user());

        return $query;
    }

}