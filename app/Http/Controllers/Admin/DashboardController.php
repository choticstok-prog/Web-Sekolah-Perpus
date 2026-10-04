<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $today = Carbon::now()->startOfDay()->format('Y-m-d');

        $stats = [
            'total_books' => Book::count(),
            'total_categories' => Category::count(),
            'total_members' => User::where('role', 'peminjam')->count(),
            'active_borrowings' => Borrowing::where('status', 'dipinjam')->count(),
            'pending_borrowings' => Borrowing::where('status', 'menunggu')->count(),
            'returned_borrowings' => Borrowing::where('status', 'dikembalikan')->count(),
            'overdue_borrowings' => Borrowing::where('status', 'dipinjam')
                ->where('due_date', '<', $today)
                ->count(),
            'total_fines' => Borrowing::sum('fine_amount'),
        ];

        $recentBorrowings = Borrowing::with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        $lowStockBooks = Book::with('category')
            ->where('available_stock', '<=', 1)
            ->take(5)
            ->get();

        $recentMembers = User::where('role', 'peminjam')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentBorrowings' => $recentBorrowings,
            'lowStockBooks' => $lowStockBooks,
            'recentMembers' => $recentMembers,
        ]);
    }
}
