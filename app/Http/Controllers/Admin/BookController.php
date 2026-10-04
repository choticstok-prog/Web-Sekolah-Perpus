<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of books for admin.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $query = Book::with('category')->latest();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $books = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.books.index', [
            'books' => $books,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }

    /**
     * Show the form for creating a new book.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.books.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'source_url' => ['nullable', 'url', 'max:500'],
            'pdf_url' => ['nullable', 'url', 'max:500'],
            'cover_url' => ['nullable', 'string', 'max:500'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Nama pengarang wajib diisi.',
            'publisher.required' => 'Nama penerbit wajib diisi.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'stock.required' => 'Jumlah stok buku wajib diisi.',
            'stock.min' => 'Stok buku minimal 1.',
        ]);

        $coverImage = null;
        if ($request->hasFile('cover_file')) {
            $path = $request->file('cover_file')->store('covers', 'public');
            $coverImage = '/storage/'.$path;
        } elseif (! empty($validated['cover_url'])) {
            $coverImage = $validated['cover_url'];
        }

        Book::create([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'],
            'city' => $validated['city'] ?? null,
            'year' => $validated['year'],
            'isbn' => $validated['isbn'] ?? null,
            'stock' => $validated['stock'],
            'available_stock' => $validated['stock'],
            'description' => $validated['description'] ?? null,
            'source_url' => $validated['source_url'] ?? null,
            'pdf_url' => $validated['pdf_url'] ?? null,
            'cover_image' => $coverImage,
        ]);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.books.edit', [
            'book' => $book,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'source_url' => ['nullable', 'url', 'max:500'],
            'pdf_url' => ['nullable', 'url', 'max:500'],
            'cover_url' => ['nullable', 'string', 'max:500'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $coverImage = $book->cover_image;
        if ($request->hasFile('cover_file')) {
            $path = $request->file('cover_file')->store('covers', 'public');
            $coverImage = '/storage/'.$path;
        } elseif (! empty($validated['cover_url'])) {
            $coverImage = $validated['cover_url'];
        }

        // Adjust available stock based on stock change
        $stockDiff = $validated['stock'] - $book->stock;
        $newAvailableStock = max(0, $book->available_stock + $stockDiff);

        $book->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'],
            'city' => $validated['city'] ?? null,
            'year' => $validated['year'],
            'isbn' => $validated['isbn'] ?? null,
            'stock' => $validated['stock'],
            'available_stock' => $newAvailableStock,
            'description' => $validated['description'] ?? null,
            'source_url' => $validated['source_url'] ?? null,
            'pdf_url' => $validated['pdf_url'] ?? null,
            'cover_image' => $coverImage,
        ]);

        return redirect()->route('admin.books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Book $book): RedirectResponse
    {
        $hasActiveLoans = $book->borrowings()->whereIn('status', ['menunggu', 'dipinjam'])->exists();

        if ($hasActiveLoans) {
            return back()->with('error', 'Buku tidak dapat dihapus karena sedang ada peminjaman aktif.');
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
