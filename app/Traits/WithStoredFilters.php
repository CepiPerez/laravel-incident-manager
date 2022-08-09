<?php

namespace App\Traits;

trait WithStoredFilters
{

    public function getStoredFilters($stored_array)
    {
        $filter_values = [
			'assigned', 'client_id', 'group_id', 'status_id', 'problem_id', 
			'module_id', 'area_id', 'pid', 'order', 'search'
		];

		$filters = [];

		$stored = session($stored_array, []);

		foreach ($filter_values as $filter)
		{
			$filters[$filter] = $this->checkFilter($filter, $stored[$filter] ?? null);
			$this->$filter = $filters[$filter];
		}

		session()->put($stored_array, $filters);
		session()->reflash();

        return $filters;
    }

    private function checkFilter($filter, $default)
	{
		if (request()->has($filter))
		{
			if (request()->$filter!='all')
				return request()->$filter;

			if (request()->$filter=='all' || request()->$filter==null)
				return null;
		}

		return $default;
	}

}