<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        $type = fake()->randomElement([
            'mobile',
            'email',
            'phone',
            'address',
        ]);

        return [
            'person_id' => Person::factory(),
            'contact_type' => $type,
            'label' => $this->labelByType($type),
            'value' => $this->valueByType($type),
        ];
    }

    /**
     * label وابسته به نوع تماس
     */
    private function labelByType(string $type): string
    {
        return match ($type) {
            'mobile'  => 'موبایل',
            'phone'   => 'تلفن ثابت',
            'email'   => 'ایمیل',
            'address' => 'آدرس',
        };
    }

    /**
     * value وابسته به نوع تماس (نکته کلیدی)
     */
    private function valueByType(string $type): string
    {
        return match ($type) {
            'mobile'  => fake()->phoneNumber(),
            'phone'   => fake()->phoneNumber(),
            'email'   => fake()->safeEmail(),
            'address' => fake()->address(),
        };
    }
}
