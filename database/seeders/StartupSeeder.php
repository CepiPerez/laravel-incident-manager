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

class StartupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::create([
            'username'  => 'admin',
            'name'      => 'Administrator',
            'email'     => 'admin@admin.com',
            'password'  => Hash::make('admin'),
            'type'      => 1,
            'active'    => 1,
            'role_id'   => 1,
            'client_id' => 0
        ]);
        $admin->groups()->attach([1, 2]);

        Permission::create(['description' => 'incidents_create']);
        Permission::create(['description' => 'incidents_all']);
        Permission::create(['description' => 'incidents_own']);
        Permission::create(['description' => 'incidents_client']);
        Permission::create(['description' => 'mass_creation']);
        Permission::create(['description' => 'dashboard']);
        Permission::create(['description' => 'reports']);
        Permission::create(['description' => 'admin_users_all']);
        Permission::create(['description' => 'admin_users_external']);
        Permission::create(['description' => 'admin_roles']);
        Permission::create(['description' => 'admin_clients']);
        Permission::create(['description' => 'admin_areas']);
        Permission::create(['description' => 'admin_modules']);
        Permission::create(['description' => 'admin_problems']);
        Permission::create(['description' => 'admin_service_types']);
        Permission::create(['description' => 'admin_progress_types']);

        Role::create(['description' => 'Administrator', 'type' => 1]);
        
        $role = Role::create(['description' => 'Support user', 'type' => 1]);
            $role->permissions()->sync([1, 2, 5, 6, 7]);

        $role2 = Role::create(['description' => 'External user', 'type' => 0]);
            $role2->permissions()->sync([1, 3, 6]);

        Sla::create(['sla_default' => 48, 'sla_notify' => 8]);

        ProgressType::create(['id' => 1, 'description' => 'take', 'creator_visible' => 1, 'creator_email' => 0, 'internal_email' => 0]);
        ProgressType::create(['id' => 2, 'description' => 'derivation', 'creator_visible' => 1, 'creator_email' => 0, 'internal_email' => 0]);
        ProgressType::create(['id' => 4, 'description' => 'observation', 'creator_visible' => 1, 'creator_email' => 0, 'internal_email' => 0]);
        ProgressType::create(['id' => 5, 'description' => 'waiting', 'creator_visible' => 1, 'creator_email' => 0, 'internal_email' => 0]);
        ProgressType::create(['id' => 6, 'description' => 'reopen', 'creator_visible' => 1, 'creator_email' => 0, 'internal_email' => 0]);
        ProgressType::create(['id' => 10, 'description' => 'resolution', 'creator_visible' => 1, 'creator_email' => 0, 'internal_email' => 0]);
        ProgressType::create(['id' => 20, 'description' => 'close', 'creator_visible' => 1, 'creator_email' => 0, 'internal_email' => 0]);
        ProgressType::create(['id' => 30, 'description' => 'client_note', 'creator_visible' => 1, 'creator_email' => 0, 'internal_email' => 0]);
        ProgressType::create(['id' => 50, 'description' => 'cancelation', 'creator_visible' => 1, 'creator_email' => 0, 'internal_email' => 0]);
        ProgressType::create(['id' => 100, 'description' => 'private_note', 'creator_visible' => 0, 'creator_email' => 0, 'internal_email' => 0]);
        ProgressType::create(['id' => 101, 'description' => 'create', 'creator_visible' => 0, 'creator_email' => 0, 'internal_email' => 0]);

        Priority::create(['description' => 'no_priority', 'min' => 0, 'max' => 0]);
        Priority::create(['description' => 'low', 'min' => 1, 'max' => 39]);
        Priority::create(['description' => 'medium', 'min' => 40, 'max' => 79]);
        Priority::create(['description' => 'high', 'min' => 80, 'max' => 109]);
        Priority::create(['description' => 'critical', 'min' => 110, 'max' => 1000]);

        IncidentState::create(['id' => 0, 'description' => 'unassigned']);
        IncidentState::create(['id' => 1, 'description' => 'in_progress']);
        IncidentState::create(['id' => 5, 'description' => 'paused']);
        IncidentState::create(['id' => 10, 'description' => 'resolved']);
        IncidentState::create(['id' => 20, 'description' => 'closed']);
        IncidentState::create(['id' => 50, 'description' => 'canceled']);

        //Problem::create(['description' => 'CPU', 'points' => 20, 'active' => 1]);
        //Problem::create(['description' => 'Monitor', 'points' => 15, 'active' => 1]);
        //Problem::create(['description' => 'Peripherals', 'points' => 10, 'active' => 1]);
        //Problem::create(['description' => 'Operating System', 'points' => 20, 'active' => 1]);
        //Problem::create(['description' => 'Office applications', 'points' => 10, 'active' => 1]);
        
        //$m1 = Module::create(['description' => 'Hardware', 'points' => 20, 'active' => 1]);
        //$m1->problems()->sync([1, 2, 3]);

        //$m2 = Module::create(['description' => 'Software', 'points' => 10, 'active' => 1]);
        //$m2->problems()->sync([4, 5]);

        //$a = Area::create(['description' => 'It', 'points' => 10]);
        //$a->modules()->sync([1, 2]);

        //ServiceType::create(['description' => 'Basic', 'points' => 10]);
        //ServiceType::create(['description' => 'Premium', 'points' => 20]);

        //$g = Group::create(['description' => 'Tech support']);
        //$g = Group::create(['description' => 'Field support']);

    }
}
