<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\BorrowingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'book_id',
    'borrow_code',
    'borrow_date',
    'due_date',
    'return_date',
    'status',
    'fine_amount',
    'notes',
    'admin_notes',
])]
class Borrowing extends Model
{
    /** @use HasFactory<BorrowingFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
            'fine_amount' => 'integer',
        ];
    }

    /**
     * User who borrowed the book.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Book being borrowed.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if the borrowing is currently overdue.
     */
    public function isOverdue(): bool
    {
        if ($this->status !== 'dipinjam') {
            return false;
        }

        return Carbon::now()->startOfDay()->greaterThan($this->due_date->startOfDay());
    }

    /**
     * Calculate overdue days and fine (Rp 1,000 per day late).
     */
    public function calculateOverdueFine(int $finePerDay = 1000): int
    {
        if ($this->status !== 'dipinjam') {
            return $this->fine_amount;
        }

        $today = Carbon::now()->startOfDay();
        $dueDate = $this->due_date->startOfDay();

        if ($today->greaterThan($dueDate)) {
            $daysLate = $dueDate->diffInDays($today);

            return (int) ($daysLate * $finePerDay);
        }

        return 0;
    }
}
