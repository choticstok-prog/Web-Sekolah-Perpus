<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Display current user's borrowing history.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = Borrowing::with('book.category')
            ->where('user_id', Auth::id())
            ->latest();

        if ($status && in_array($status, ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak'], true)) {
            $query->where('status', $status);
        }

        $borrowings = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => Borrowing::where('user_id', Auth::id())->count(),
            'dipinjam' => Borrowing::where('user_id', Auth::id())->where('status', 'dipinjam')->count(),
            'menunggu' => Borrowing::where('user_id', Auth::id())->where('status', 'menunggu')->count(),
            'dikembalikan' => Borrowing::where('user_id', Auth::id())->where('status', 'dikembalikan')->count(),
        ];

        return view('borrowings.index', [
            'borrowings' => $borrowings,
            'status' => $status,
            'stats' => $stats,
        ]);
    }

    /**
     * Store a newly created borrowing request by member.
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:14'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'borrow_date.required' => 'Tanggal pinjam wajib diisi.',
            'borrow_date.after_or_equal' => 'Tanggal pinjam tidak boleh sebelum hari ini.',
            'duration_days.required' => 'Lama peminjaman wajib dipilih.',
            'duration_days.min' => 'Lama peminjaman minimal 1 hari.',
            'duration_days.max' => 'Lama peminjaman maksimal 14 hari.',
        ]);

        if (! $book->isAvailable()) {
            return back()->with('error', 'Maaf, stok buku saat ini sedang habis.');
        }

        // Check if member already has an active loan for this book
        $alreadyBorrowing = Borrowing::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->exists();

        if ($alreadyBorrowing) {
            return back()->with('error', 'Anda sudah mengajukan atau sedang meminjam buku ini.');
        }

        $borrowDate = Carbon::parse($validated['borrow_date']);
        $dueDate = (clone $borrowDate)->addDays((int) $validated['duration_days']);

        $borrowCode = 'BRW-'.date('Ymd').'-'.strtoupper(Str::random(4));

        DB::transaction(function () use ($book, $borrowCode, $borrowDate, $dueDate, $validated): void {
            Borrowing::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'borrow_code' => $borrowCode,
                'borrow_date' => $borrowDate->format('Y-m-d'),
                'due_date' => $dueDate->format('Y-m-d'),
                'status' => 'menunggu',
                'fine_amount' => 0,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()->route('borrowings.index')
            ->with('success', 'Permintaan peminjaman berhasil diajukan! Menunggu persetujuan petugas perpustakaan.');
    }

    /**
     * Cancel a pending borrowing request.
     */
    public function cancel(Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Hanya pengajuan dengan status "Menunggu" yang dapat dibatalkan.');
        }

        $borrowing->delete();

        return redirect()->route('borrowings.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }
}
