@extends('layouts.public')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold">Контакти</h1>
    <p class="mt-2 text-sm text-gray-600">Маєте питання? Заповніть форму — ми відповімо на ваш email.</p>

    <form method="POST" action="{{ route('contact.send') }}" class="mt-6 space-y-4 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Ім'я</label>
            <input id="name" name="name" type="text" value="{{ old('name', auth()->user()?->name) }}" required
                   class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', auth()->user()?->email) }}" required
                   class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700">Повідомлення</label>
            <textarea id="message" name="message" rows="5" required
                      class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">{{ old('message') }}</textarea>
            @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Надіслати</button>
    </form>
</div>
@endsection
