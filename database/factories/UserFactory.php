<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = $this->faker->numberBetween(1, 2);
        $role_id = $type==1? $this->faker->numberBetween(1, 2) : 3;
        $clients = Client::count(); 
        $client_id = $type==1? 0 : $this->faker->numberBetween(1, $clients);
        $name = $this->faker->firstName() . ' ' . $this->faker->lastName();
        $username = strtolower(str_replace(' ', '.', $name));

        return [
            'name' => $name,
            'username' => $username,
            'email' => $username . '@' . $this->faker->safeEmailDomain(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'type' => $type,
            'active' => 1,
            'role_id' => $role_id,
            'client_id' => $client_id
        ];
    }

    
}
