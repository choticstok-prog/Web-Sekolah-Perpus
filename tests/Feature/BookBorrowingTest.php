<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookBorrowingTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_can_be_browsed_by_guest(): void
    {
        $category = Category::factory()->create(['name' => 'Fiksi Ilmiah']);
        $book = Book::factory()->create([
            'category_id' => $category->id,
            'title' => 'Perjalanan ke Bulan',
        ]);

        $response = $this->get(route('catalog.index'));

        $response->assertStatus(200);
        $response->assertSee('Perjalanan ke Bulan');
        $response->assertSee('Fiksi Ilmiah');
    }

    public function test_book_details_can_be_viewed(): void
    {
        $book = Book::factory()->create(['title' => 'Panduan Laravel Lengkap']);

        $response = $this->get(route('catalog.show', $book));

        $response->assertStatus(200);
        $response->assertSee('Panduan Laravel Lengkap');
    }

    public function test_member_can_submit_borrow_request(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);
        $book = Book::factory()->create([
            'stock' => 5,
            'available_stock' => 5,
        ]);

        $response = $this->actingAs($user)->post(route('borrowings.store', $book), [
            'borrow_date' => now()->format('Y-m-d'),
            'duration_days' => 7,
            'notes' => 'Pinjam untuk referensi skripsi',
        ]);

        $response->assertRedirect(route('borrowings.index'));
        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'menunggu',
            'notes' => 'Pinjam untuk referensi skripsi',
        ]);
    }

    public function test_member_cannot_borrow_unavailable_book(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);
        $book = Book::factory()->create([
            'stock' => 1,
            'available_stock' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('borrowings.store', $book), [
            'borrow_date' => now()->format('Y-m-d'),
            'duration_days' => 7,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Pengelola Perpustakaan');
    }

    public function test_member_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'peminjam']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_approve_borrowing_and_decrease_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create([
            'stock' => 5,
            'available_stock' => 5,
        ]);
        $borrowing = Borrowing::factory()->create([
            'book_id' => $book->id,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.borrowings.approve', $borrowing), [
            'admin_notes' => 'Disetujui admin.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'status' => 'dipinjam',
        ]);

        $this->assertEquals(4, $book->fresh()->available_stock);
    }

    public function test_admin_can_confirm_return_and_restore_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create([
            'stock' => 5,
            'available_stock' => 4,
        ]);
        $borrowing = Borrowing::factory()->create([
            'book_id' => $book->id,
            'status' => 'dipinjam',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.borrowings.returnConfirm', $borrowing), [
            'fine_amount' => 0,
            'admin_notes' => 'Buku kembali lengkap.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'status' => 'dikembalikan',
        ]);

        $this->assertEquals(5, $book->fresh()->available_stock);
    }

    public function test_book_details_page_shows_author_year_title_city_and_source_url(): void
    {
        $book = Book::factory()->create([
            'title' => 'Sejarah Indonesia Fase E Kelas X',
            'author' => 'Sari Oktafiana dkk',
            'publisher' => 'Kemendikbudristek',
            'city' => 'Jakarta Pusat',
            'year' => 2023,
            'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/sejarah-x',
            'cover_image' => '/images/covers/sejarah-indonesia-kelas-x.png',
        ]);

        $response = $this->get(route('catalog.show', $book));

        $response->assertStatus(200);
        $response->assertSee('Sejarah Indonesia Fase E Kelas X');
        $response->assertSee('Sari Oktafiana dkk');
        $response->assertSee('2023');
        $response->assertSee('Jakarta Pusat');
        $response->assertSee('https://buku.kemendikdasmen.go.id/katalog/sejarah-x');
        $response->assertSee('/images/covers/sejarah-indonesia-kelas-x.png');
    }

    public function test_admin_can_create_and_update_book_with_city_and_source_url(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $createResponse = $this->actingAs($admin)->post(route('admin.books.store'), [
            'category_id' => $category->id,
            'title' => 'Sejarah Pergerakan Nasional Kelas XI',
            'author' => 'Dr. Martina Safitry',
            'publisher' => 'Pusat Perbukuan',
            'city' => 'Bandung',
            'year' => 2022,
            'isbn' => '978-602-244-862-4',
            'stock' => 10,
            'description' => 'Ringkasan materi sejarah pergerakan kebangsaan',
            'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/sejarah-xi',
            'cover_url' => '/images/covers/sejarah-indonesia-semester-1-kelas-xi.png',
        ]);

        $createResponse->assertRedirect(route('admin.books.index'));
        $this->assertDatabaseHas('books', [
            'title' => 'Sejarah Pergerakan Nasional Kelas XI',
            'city' => 'Bandung',
            'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/sejarah-xi',
        ]);

        $book = Book::where('title', 'Sejarah Pergerakan Nasional Kelas XI')->first();

        $updateResponse = $this->actingAs($admin)->put(route('admin.books.update', $book), [
            'category_id' => $category->id,
            'title' => 'Sejarah Pergerakan Nasional Kelas XI (Edisi Revisi)',
            'author' => 'Dr. Martina Safitry',
            'publisher' => 'Pusat Perbukuan',
            'city' => 'Jakarta Selatan',
            'year' => 2023,
            'isbn' => '978-602-244-862-4',
            'stock' => 12,
            'description' => 'Ringkasan materi sejarah pergerakan kebangsaan revisi',
            'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/sejarah-xi-revisi',
            'cover_url' => '/images/covers/sejarah-indonesia-semester-1-kelas-xi.png',
        ]);

        $updateResponse->assertRedirect(route('admin.books.index'));
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Sejarah Pergerakan Nasional Kelas XI (Edisi Revisi)',
            'city' => 'Jakarta Selatan',
            'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/sejarah-xi-revisi',
        ]);
    }

    public function test_catalog_can_be_filtered_by_kelas_and_baca_online(): void
    {
        $category = Category::factory()->create(['name' => 'Matematika']);
        $bookWithPdf = Book::factory()->create([
            'category_id' => $category->id,
            'title' => 'Matematika Kelas 10 SMA',
            'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/matematika_x.pdf',
        ]);
        $bookWithoutPdf = Book::factory()->create([
            'category_id' => $category->id,
            'title' => 'Matematika Kelas 12 SMA',
            'pdf_url' => null,
        ]);

        $response = $this->get(route('catalog.index', ['kelas' => '10', 'baca_online' => 1]));
        $response->assertStatus(200);
        $response->assertSee('Matematika Kelas 10 SMA');
        $response->assertDontSee('Matematika Kelas 12 SMA');
    }

    public function test_reader_interface_loads_successfully_for_online_books(): void
    {
        $book = Book::factory()->create([
            'title' => 'Sejarah Indonesia Kelas X',
            'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/sejarah_x.pdf',
            'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/sejarah-x',
        ]);

        $response = $this->get(route('catalog.read', $book));

        $response->assertStatus(200);
        $response->assertSee('Baca Online: Sejarah Indonesia Kelas X');
        $response->assertSee('Google Docs Viewer');
        $response->assertSee('Catatan Siswa');
    }

    public function test_database_seeder_produces_unique_covers_and_valid_source_urls(): void
    {
        $this->seed();

        $books = Book::all();
        $this->assertGreaterThanOrEqual(25, $books->count());

        $covers = [];
        foreach ($books as $book) {
            $this->assertNotEmpty($book->source_url, "Book {$book->title} must have a source URL");
            $this->assertStringStartsWith('https://buku.kemendikdasmen.go.id/katalog/', $book->source_url);
            $this->assertNotEmpty($book->pdf_url, "Book {$book->title} must have a PDF URL");
            $this->assertStringStartsWith('https://', $book->pdf_url);
            $this->assertNotEmpty($book->cover_image, "Book {$book->title} must have a cover image");

            $localCover = public_path($book->cover_image);
            $this->assertFileExists($localCover, "Cover image file must exist: {$localCover}");

            $hash = md5_file($localCover);
            $this->assertArrayNotHasKey($hash, $covers, "Duplicate cover found for {$book->title} and ".($covers[$hash] ?? ''));
            $covers[$hash] = $book->title;
        }
    }
}
