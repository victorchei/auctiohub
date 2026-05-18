<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact.show');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|min:10|max:2000',
        ]);

        Log::info('Contact form submission', $data);

        return redirect()->route('contact.show')->with('status', 'Дякуємо! Ваше повідомлення надіслано.');
    }
}
