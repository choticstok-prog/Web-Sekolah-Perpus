<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Display a listing of library members.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $query = User::where('role', 'peminjam')
            ->withCount([
                'borrowings as total_borrowings',
                'borrowings as active_borrowings' => function ($q): void {
                    $q->where('status', 'dipinjam');
                },
            ])
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $members = $query->paginate(10)->withQueryString();

        return view('admin.members.index', [
            'members' => $members,
            'search' => $search,
        ]);
    }

    /**
     * Display the specified member details and their borrowing history.
     */
    public function show(User $user): View
    {
        $user->loadCount([
            'borrowings as total_borrowings',
            'borrowings as active_borrowings' => function ($q): void {
                $q->where('status', 'dipinjam');
            },
        ]);

        $borrowings = $user->borrowings()
            ->with('book.category')
            ->latest()
            ->paginate(10);

        return view('admin.members.show', [
            'member' => $user,
            'borrowings' => $borrowings,
        ]);
    }
}
