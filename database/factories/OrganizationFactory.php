<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company();
        return [
            'name' => $name,
            'code' => Str::slug($name),
            'parent_id' => null,
        ];
    }

    public function childOf(Organization $parent)
    {
        return $this->state(function () use ($parent) {
            return [
                'parent_id' => $parent->id,
            ];
        });
    }
}
