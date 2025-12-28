<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::factory()->count(10)->create();
        Organization::all()->each(function (Organization $unit) {

        // Level 0 – Head Role
        $head = Role::factory()->create([
            'name' => $unit->name . ' Director',
            'organization_id' => $unit->id,
        ]);

        // Level 1 – Managers
        $managers = Role::factory()
            ->count(rand(1, 3))
            ->create()
            ->each(fn ($role) =>
            $role->update([
                'parent_id' => $head->id,
                'organization_id' => $unit->id,
            ])
            );

        // Level 2 – Seniors
        $managers->each(function (Role $manager) use ($unit) {
            $seniors = Role::factory()
                ->count(rand(1, 3))
                ->create()
                ->each(fn ($role) =>
                $role->update([
                    'parent_id' => $manager->id,
                    'organization_id' => $unit->id,
                ])
                );

            // Level 3 – Juniors
            $seniors->each(function (Role $senior) use ($unit) {
                Role::factory()
                    ->count(rand(1, 2))
                    ->create()
                    ->each(fn ($role) =>
                    $role->update([
                        'parent_id' => $senior->id,
                        'organization_id' => $unit->id,
                    ])
                    );
            });
        });
    });
    }
}
