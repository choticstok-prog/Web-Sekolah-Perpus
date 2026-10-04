<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stock = fake()->numberBetween(1, 15);

        return [
            'category_id' => Category::factory(),
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'publisher' => fake()->company(),
            'city' => fake()->randomElement(['Jakarta', 'Bandung', 'Yogyakarta', 'Surabaya']),
            'year' => fake()->numberBetween(2015, 2026),
            'isbn' => fake()->isbn13(),
            'stock' => $stock,
            'available_stock' => $stock,
            'description' => fake()->paragraph(),
            'source_url' => 'https://buku.kemdikbud.go.id',
            'pdf_url' => 'https://static-sc.cloudapp.web.id/sample.pdf',
            'cover_image' => null,
        ];
    }
}
