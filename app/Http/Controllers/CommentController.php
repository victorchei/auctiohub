<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Lot;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Lot $lot)
    {
        Comment::create([
            'lot_id' => $lot->id,
            'user_id' => $request->user()->id,
            'parent_id' => $request->input('parent_id'),
            'body' => $request->input('body'),
        ]);

        return back()->with('status', 'Коментар додано.');
    }

    public function destroy(Request $request, Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return back()->with('status', 'Коментар видалено.');
    }
}
