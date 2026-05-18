<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->withCount(['lots', 'bids']);

        if ($request->filled('q')) {
            $term = '%'.$request->q.'%';
            $query->where(fn ($x) => $x->where('name', 'like', $term)->orWhere('email', 'like', $term));
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->boolean('banned_only')) {
            $query->whereNotNull('banned_at');
        }

        $users = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function ban(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Адміністратора не можна забанити.');
        }
        $user->update(['banned_at' => now()]);

        return back()->with('status', "Користувач «{$user->name}» заблокований.");
    }

    public function unban(User $user)
    {
        $user->update(['banned_at' => null]);

        return back()->with('status', "Користувач «{$user->name}» розблокований.");
    }

    public function promote(User $user)
    {
        $user->update(['role' => 'admin']);

        return back()->with('status', "«{$user->name}» отримав права адміністратора.");
    }
}
