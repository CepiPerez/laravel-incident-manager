<?php

namespace App\Traits;

trait WithMixedFilters
{
	public $assigned;
    public $group_id;
    public $client_id;
    public $area_id;
    public $module_id;
    public $problem_id;
    public $pid;
    public $status_id;
    public $user;
    public $order;
    public $search;


	public function setFilters($filters)
	{
		foreach ($filters as $key => $val)
			$this->$key = $val;

		//$this->resetPage();
	}

	public function sortBy($val)
	{
		$this->order = $val;
	}

	public function setSearch($value)
    {
		/* $stored = session('filters', []);
		$stored['search'] = $value;
		session()->put('filters', $stored);
		session()->reflash(); */

		$this->search = $value;
		//$this->resetPage();
    }


    public function getMixedFilters($store_value)
    {
        $filter_values = [
			'assigned', 'client_id', 'group_id', 'status_id', 'problem_id', 
			'module_id', 'area_id', 'pid', 'order', 'search'
		];

		$filters = [];

		$stored = session($store_value, []);

		foreach ($filter_values as $filter)
		{
			$filters[$filter] = $this->checkFilter($filter, $stored[$filter] ?? null);
			$this->$filter = $filters[$filter];
		}

		session()->put($store_value, $filters);
		session()->reflash();

		//dd($filters);
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

	private function checkLivewireFilter($filter, $default)
	{
		//return $this->$filter=='all'? null : $this->$filter;
		if ($this->$filter!==null)
		{
			return $this->$filter=='all'? null : $this->$filter;
		}

		return $default;
	}

}