<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Client;
use App\Models\Group;
use App\Models\IncidentState;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Priority;
use App\Models\Problem;
use App\Models\ProgressType;
use App\Models\Role;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Sla;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestStartSeeded extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Problem::create(['description' => 'CPU', 'points' => 20, 'active' => 1]);
        Problem::create(['description' => 'Monitor', 'points' => 15, 'active' => 1]);
        Problem::create(['description' => 'Peripherals', 'points' => 10, 'active' => 1]);
        Problem::create(['description' => 'Operating System', 'points' => 20, 'active' => 1]);
        Problem::create(['description' => 'Office applications', 'points' => 10, 'active' => 1]);
        
        $m1 = Module::create(['description' => 'Hardware', 'points' => 20, 'active' => 1]);
        $m1->problems()->sync([1, 2, 3]);

        $m2 = Module::create(['description' => 'Software', 'points' => 10, 'active' => 1]);
        $m2->problems()->sync([4, 5]);

        $a = Area::create(['description' => 'It', 'points' => 10]);
        $a->modules()->sync([1, 2]);

        ServiceType::create(['description' => 'Basic', 'points' => 10]);
        ServiceType::create(['description' => 'Premium', 'points' => 20]);

    }
}
