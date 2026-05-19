<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LotModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = Lot::withTrashed()->with(['seller', 'category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $query->whereRaw('LOWER(title) LIKE ?', ['%'.mb_strtolower($request->q).'%']);
        }
        if ($request->boolean('trashed')) {
            $query->onlyTrashed();
        }

        $lots = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.lots.index', compact('lots'));
    }

    public function cancel(Lot $lot)
    {
        if ($lot->status !== 'active') {
            return back()->with('error', "Лот «{$lot->title}» неможливо скасувати (статус: {$lot->status}).");
        }
        $lot->update(['status' => 'cancelled']);

        return back()->with('status', "Лот «{$lot->title}» скасовано.");
    }

    public function destroy(Lot $lot)
    {
        $lot->delete();

        return back()->with('status', 'Лот переміщено у Trash.');
    }

    public function restore(int $id)
    {
        $lot = Lot::withTrashed()->findOrFail($id);
        $lot->restore();

        return back()->with('status', 'Лот відновлено.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $count = Lot::whereIn('id', $ids)->delete();

        return back()->with('status', "Видалено {$count} лотів.");
    }

    public function exportCsv(): StreamedResponse
    {
        // Eager-load + withCount уникає N+1 для bids
        $lots = Lot::withTrashed()->with('seller', 'category')->withCount('bids')->get();

        return response()->streamDownload(function () use ($lots) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Title', 'Seller', 'Category', 'Status', 'Current Price', 'Bids', 'Ends At']);
            foreach ($lots as $lot) {
                fputcsv($out, [
                    $lot->id,
                    $lot->title,
                    $lot->seller->name ?? '—',
                    $lot->category->name ?? '—',
                    $lot->status,
                    $lot->current_price,
                    $lot->bids_count,
                    $lot->ends_at?->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, 'auctiohub-lots-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }
}
