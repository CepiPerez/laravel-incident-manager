<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    protected $faker;

    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = Group::pluck('id');        

        User::factory()->count(50)->create()
            ->each(function($user) use ($groups) {
                if ($user->type==1) {
                    $user->groups()->attach($this->faker->randomElement($groups));
                }
            });
    }
}
