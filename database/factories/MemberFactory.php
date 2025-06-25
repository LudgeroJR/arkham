<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'avatar' => 'avatar.png',
            'whatsapp' => $this->faker->phoneNumber,
            'discord' => $this->faker->userName,
            'role_id' => Role::inRandomOrder()->first()->id ?? 1,
            'start_in' => $this->faker->date(),
        ];
    }
}