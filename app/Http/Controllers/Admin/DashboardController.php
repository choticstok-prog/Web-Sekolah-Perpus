<?php

/*
 * HINT: Fitur Dashboard Admin (URL: /admin)
 * Fungsi: Mengambil ringkasan statistik, 8 buku terbaru, dan 5 siswa terbaru
 *         dari database, lalu mengirimkannya ke tampilan admin/dashboard.blade.php.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard for the online library.
     */
    public function index(): View
    {
        // 1. Hitung ringkasan angka statistik untuk kartu di atas dashboard
        $stats = [
            'total_books' => Book::count(),
            'total_online' => Book::whereNotNull('pdf_url')->count(),
            'total_categories' => Category::count(),
            'total_members' => User::where('role', 'peminjam')->count(),
            'sma_books' => Book::where('title', 'like', '%SMA%')->count(),
            'smp_books' => Book::where('title', 'like', '%SMP%')->count(),
            'novel_books' => Book::whereHas('category', function ($q): void {
                $q->where('slug', 'novel-fiksi');
            })->count(),
        ];

        // 2. Ambil 8 buku terbaru sekaligus data kategorinya
        $recentBooks = Book::with('category')
            ->latest()
            ->take(8)
            ->get();

        // 3. Ambil 5 siswa/anggota yang paling baru mendaftar
        $recentMembers = User::where('role', 'peminjam')
            ->latest()
            ->take(5)
            ->get();

        // 4. Kirim semua data di atas ke file tampilan admin/dashboard.blade.php
        return view('admin.dashboard', [
            'stats' => $stats,
            'recentBooks' => $recentBooks,
            'recentMembers' => $recentMembers,
        ]);
    }
}
