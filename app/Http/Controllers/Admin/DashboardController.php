<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'lots_total' => Lot::withTrashed()->count(),
            'lots_active' => Lot::where('status', 'active')->count(),
            'bids' => Bid::count(),
            'banned' => User::whereNotNull('banned_at')->count(),
        ];

        $bidsLast30Days = Bid::where('placed_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(placed_at) as day'), DB::raw('COUNT(*) as count'))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $topSellers = User::select('users.id', 'users.name', DB::raw('COUNT(lots.id) as lots_count'))
            ->leftJoin('lots', 'lots.seller_id', '=', 'users.id')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('lots_count')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'bidsLast30Days', 'topSellers'));
    }
}
