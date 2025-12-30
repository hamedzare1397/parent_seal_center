<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->jobTitle(),
            'label' => $this->faker->jobTitle(),
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

    public function forOrganization(Organization $organization=null)
    {
        return $this->for($organization ?? Organization::factory(),
        'organization');

    }
}
