<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->jobTitle(),
            'organization_id' => Organization::inRandomOrder()->value('id'),
            'parent_id' => null,
        ];
    }

    public function childOf(Role $parent)
    {
        return $this->state(function () use ($parent) {
            return [
                'parent_id' => $parent->id,
                'organization_id' => $parent->org_unit_id, // 🔥 خیلی مهم
            ];
        });
    }
}
