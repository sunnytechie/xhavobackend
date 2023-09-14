<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Category::class;

    protected $businessTitles = [
        'Restaurant',
        'Consulting Agency',
        'Mechanic',
        'Lawyer',
        'Accountant',
        'Real Estate Agency',
        'Plumber',
        'Electrician',
        'Painter',
        'Hairdresser',
        'Barber',
        'Tattoo Artist',
        'Photographer',
        'Videographer',
        'Graphic Designer',
        'Web Designer',
        'Web Developer',
        'Software Developer',
        'Mobile App Developer',
        'Baker',
        'Butcher',
        'Florist',
        'Landscaper',
        'Gardener',
        'Interior Designer',
        'Architect',
        'Carpenter',
        'Mason',

    ];

    public function definition(): array
    {
        $businessTitle = $this->faker->randomElement($this->businessTitles);
        return [
            'title' => $businessTitle,
            'thumbnail' => $this->faker->imageUrl(),
        ];
    }
}
