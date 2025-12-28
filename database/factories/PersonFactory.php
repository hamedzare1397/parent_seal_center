<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name'  => fake()->lastName(),
            'father_name'=>fake()->firstNameMale(),
            'mother_name'=>fake()->firstNameFemale(),
            'national_id'=>fake()->numberBetween(1000000000,9999999999),
            'birth_date'  =>fake()->date(),
            'nationality'=>fake()->country(),
            'gender'  =>fake()->randomElement(['male','female','other']),
        ];
    }

    /**
     * هر شخص به‌صورت پیش‌فرض چند راه ارتباطی داشته باشد
     */
    public function withContacts(int $count = 3)
    {
        return $this->has(
            \App\Models\Contact::factory()->count($count)
        );
    }
}
