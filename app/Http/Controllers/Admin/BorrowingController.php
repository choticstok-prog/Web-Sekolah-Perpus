<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Display a listing of borrowings for admin.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Borrowing::with(['user', 'book'])->latest();

        if ($status && in_array($status, ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak'], true)) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('borrow_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search): void {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('book', function ($bq) use ($search): void {
                        $bq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $borrowings = $query->paginate(10)->withQueryString();

        $counts = [
            'total' => Borrowing::count(),
            'menunggu' => Borrowing::where('status', 'menunggu')->count(),
            'dipinjam' => Borrowing::where('status', 'dipinjam')->count(),
            'dikembalikan' => Borrowing::where('status', 'dikembalikan')->count(),
            'ditolak' => Borrowing::where('status', 'ditolak')->count(),
        ];

        return view('admin.borrowings.index', [
            'borrowings' => $borrowings,
            'status' => $status,
            'search' => $search,
            'counts' => $counts,
        ]);
    }

    /**
     * Approve a pending borrowing.
     */
    public function approve(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Hanya peminjaman dengan status "Menunggu" yang dapat disetujui.');
        }

        $book = $borrowing->book;

        if ($book->available_stock <= 0) {
            return back()->with('error', 'Stok buku ini sedang habis, tidak dapat disetujui.');
        }

        DB::transaction(function () use ($borrowing, $book, $request): void {
            $book->decrement('available_stock');

            $borrowing->update([
                'status' => 'dipinjam',
                'admin_notes' => $request->input('admin_notes', 'Disetujui oleh admin.'),
            ]);
        });

        return back()->with('success', "Peminjaman {$borrowing->borrow_code} berhasil disetujui.");
    }

    /**
     * Reject a pending borrowing.
     */
    public function reject(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Hanya peminjaman dengan status "Menunggu" yang dapat ditolak.');
        }

        $borrowing->update([
            'status' => 'ditolak',
            'admin_notes' => $request->input('admin_notes', 'Permintaan ditolak oleh admin.'),
        ]);

        return back()->with('success', "Peminjaman {$borrowing->borrow_code} telah ditolak.");
    }

    /**
     * Confirm return of the borrowed book and apply fines if overdue.
     */
    public function returnConfirm(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== 'dipinjam') {
            return back()->with('error', 'Hanya peminjaman aktif yang dapat dikonfirmasi pengembaliannya.');
        }

        $today = Carbon::now();
        $autoFine = $borrowing->calculateOverdueFine();
        $fineAmount = $request->has('fine_amount') ? (int) $request->input('fine_amount') : $autoFine;

        DB::transaction(function () use ($borrowing, $today, $fineAmount, $request): void {
            $borrowing->book->increment('available_stock');

            $borrowing->update([
                'status' => 'dikembalikan',
                'return_date' => $today->format('Y-m-d'),
                'fine_amount' => $fineAmount,
                'admin_notes' => $request->input('admin_notes', 'Buku telah dikembalikan dan diverifikasi.'),
            ]);
        });

        return back()->with('success', "Buku untuk {$borrowing->borrow_code} berhasil dikonfirmasi pengembaliannya.");
    }
}
