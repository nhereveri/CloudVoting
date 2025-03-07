<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'can_vote' => true,
            'is_supervisor' => false,
            'is_admin' => false,
        ];
    }

    public function supervisor(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_supervisor' => true,
            ];
        });
    }

    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => true,
                'is_supervisor' => true,
            ];
        });
    }
}