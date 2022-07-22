<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Incident>
 */
class IncidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $module = $this->faker->numberBetween(1, 2);
        $problem = $module==1? $this->faker->numberBetween(1, 3) : $this->faker->numberBetween(4, 5);
        $creator = $this->faker->randomNumber(1, 100);
        $user = User::find($creator);
        $internal_users = User::where('type', 1)->pluck('id');
        $client_id = $user->type==1? $this->faker->randomNumber(1, 5) : $user->client_id;
        $assigned = $this->faker->boolean(90) ? $this->faker->randomElement($internal_users) : null;
        $group = $assigned ? User::find($assigned)->groups->first()->id : null;

        return [
            'created_at' => now(),
            'title' => $this->faker->sentence(7),
            'description' => $this->faker->text(500),
            'creator' => $creator,
            'client_id' => $client_id,
            'area_id' => 1,
            'module_id' => $module,
            'problem_id' => $problem,
            'assigned' =>  $assigned,
            'group_id' => $assigned ? $group : null,
            'status_id' => $assigned? 
                ($this->faker->boolean(95) ? $this->faker->randomElement([1, 5, 10, 20]) : 50) : 0,
            'priority' => $this->faker->randomElement([10, 20, 30, 50, 80, 100, 110, 120]),
            'sla' => $this->faker->randomElement([12, 24]),
            'created_at' => $this->faker->dateTimeBetween('-2 days', now())
        ];
    }
}
