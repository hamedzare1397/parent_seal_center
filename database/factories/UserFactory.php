<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username'  => fake()->unique()->userName(),
            'person_id' => Person::factory(),
            'email'     => fake()->unique()->email(),
            'password'  => bcrypt('123'),
            'status' => fake()->randomElement(['active', 'inactive','suspended']),
        ];
    }

    /**
     * اتصال نقش‌ها به کاربر
     */
    public function withRoles(int $count = 1)
    {
        return $this->afterCreating(function (User $user) use ($count) {
            $roles = Role::query()
                ->inRandomOrder()
                ->limit($count)
                ->pluck('id');

            // اگر Role وجود نداشت، بساز
            if ($roles->isEmpty()) {
                $roles = \App\Models\Role::factory()
                    ->count($count)
                    ->create()
                    ->pluck('id');
            }

            $user->roles()->attach($roles);
        });
    }
    public function admin()
    {
        return $this->afterCreating(function (User $user) {
            $user->username='admin';
            $user->status='active';
            $user->save();
            $adminRole = \App\Models\Role::firstOrCreate(
                ['name' => 'admin'],
                ['label' => 'Admin']
            );

            $user->roles()->syncWithoutDetaching([$adminRole->id]);
        });
    }

}
