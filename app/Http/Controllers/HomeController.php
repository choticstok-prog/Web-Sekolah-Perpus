<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the application welcoming landing page.
     */
    public function index(): View
    {
        $featuredBooks = Book::with('category')
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount('books')
            ->orderBy('name')
            ->take(8)
            ->get();

        $stats = [
            'total_books' => Book::count(),
            'total_categories' => Category::count(),
            'total_online' => Book::whereNotNull('pdf_url')->count(),
            'total_stock' => (int) Book::sum('stock'),
        ];

        return view('home', [
            'featuredBooks' => $featuredBooks,
            'categories' => $categories,
            'stats' => $stats,
        ]);
    }
}
