<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookSearchAndExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_filter_catalog_by_year(): void
    {
        $cat = Category::factory()->create();

        Book::factory()->create([
            'title' => 'Buku Spesial 2009',
            'year' => 2009,
            'category_id' => $cat->id,
        ]);

        $response = $this->get('/katalog?tahun=2009');
        $response->assertStatus(200);
        $response->assertSee('2009');
        $response->assertSee('Buku Spesial 2009');
    }

    public function test_user_can_search_catalog_by_year_string(): void
    {
        $cat = Category::factory()->create();

        Book::factory()->create([
            'title' => 'Buku Sejarah Peradaban',
            'year' => 2009,
            'category_id' => $cat->id,
        ]);

        $response = $this->get('/katalog?search=2009');
        $response->assertStatus(200);
        $response->assertSee('Buku Sejarah Peradaban');
    }

    public function test_admin_can_export_book_data_pdf(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $cat = Category::factory()->create();

        Book::factory()->create([
            'title' => 'Buku Untuk Ekspor',
            'year' => 2024,
            'category_id' => $cat->id,
        ]);

        $response = $this->actingAs($admin)->get('/admin/buku/ekspor');
        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('laporan-data-buku-perpustakaan', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.pdf', $response->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF-1.4', $response->getContent());
    }

    public function test_admin_can_filter_books_by_year(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $cat = Category::factory()->create();

        Book::factory()->create([
            'title' => 'Buku Admin 2009',
            'year' => 2009,
            'category_id' => $cat->id,
        ]);

        $response = $this->actingAs($admin)->get('/admin/buku?year=2009');
        $response->assertStatus(200);
        $response->assertSee('Buku Admin 2009');
    }
}
