<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Display a listing of books in the catalog.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categorySlug = $request->query('kategori');
        $kelas = $request->query('kelas');
        $bacaOnline = $request->boolean('baca_online');

        $query = Book::with('category')->latest();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug): void {
                $q->where('slug', $categorySlug);
            });
        }

        if ($kelas) {
            $query->where(function ($q) use ($kelas): void {
                $q->where('title', 'like', "%Kelas {$kelas}%")
                    ->orWhere('title', 'like', '%Kelas '.match ($kelas) {
                        '10' => 'X',
                        '11' => 'XI',
                        '12' => 'XII',
                        default => $kelas,
                    }.'%');
            });
        }

        if ($bacaOnline) {
            $query->whereNotNull('pdf_url');
        }

        $books = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('books')->orderBy('name')->get();
        $selectedCategory = $categorySlug ? Category::where('slug', $categorySlug)->first() : null;

        $stats = [
            'total_books' => Book::count(),
            'total_online' => Book::whereNotNull('pdf_url')->count(),
            'total_categories' => Category::count(),
        ];

        return view('catalog.index', [
            'books' => $books,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
            'kelas' => $kelas,
            'bacaOnline' => $bacaOnline,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the specified book details.
     */
    public function show(Book $book): View
    {
        $book->load('category');
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('catalog.show', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
        ]);
    }

    /**
     * Display the online book reader / e-reader.
     */
    public function read(Book $book): View
    {
        $book->load('category');
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('catalog.read', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
        ]);
    }
}
