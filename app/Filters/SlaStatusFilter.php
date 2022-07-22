<?php

namespace App\Filters;

class SlaStatusFilter
{
    public static function filter($query, $value, $sla)
    {
        if ($value=='opened')
            return $query->where('status_id', '<', 10);

        elseif ($value=='ended')
            return $query->whereIn('status_id', [10, 20]);
   
        elseif ($value=='unassigned')
            return $query->where('status_id', 0);

        elseif ($value=='in_progress')
            return $query->where('status_id', 1);

        elseif ($value=='paused')
            return $query->where('status_id', 5);

        elseif ($value=='resolved')
            return $query->where('status_id', 10);

        elseif ($value=='closed')
            return $query->where('status_id', 20);

        elseif ($value=='canceled')
            return $query->where('status_id', 50);

        elseif ($value=='on_time')
            return $query->whereRaw("( (DATE_ADD(incidents.created_at, INTERVAL sla HOUR) > NOW() 
                    AND DATE_ADD(incidents.created_at, INTERVAL sla-$sla HOUR)>NOW() ) OR sla=0) AND status_id<10");
   
        elseif ($value=='to_expire')
            return $query->whereRaw("DATE_ADD(incidents.created_at, INTERVAL sla HOUR) > NOW() AND 
                    DATE_ADD(incidents.created_at, INTERVAL sla-$sla HOUR) < NOW() AND sla>0 AND status_id<10");
   
        elseif ($value=='expired')
            return $query->whereRaw('DATE_ADD(incidents.created_at, INTERVAL sla HOUR) < NOW() AND 
                    sla>0 AND status_id<10');

        return $query;
    }
}