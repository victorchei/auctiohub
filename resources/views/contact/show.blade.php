@extends('layouts.public')

@section('content')
<div class="max-w-4xl">
    <h1 class="text-2xl font-bold">{{ __('messages.contact.title') }}</h1>
    <p class="mt-2 text-sm text-gray-600">{{ __('messages.contact.subtitle') }}</p>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">

        {{-- Org info --}}
        <div class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <h2 class="mb-4 text-base font-semibold text-gray-900">{{ __('messages.contact.org_title') }}</h2>
                <ul class="space-y-3 text-sm text-gray-700">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 text-indigo-600">📍</span>
                        <span>{{ __('messages.contact.address') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 text-indigo-600">📞</span>
                        <a href="tel:+380441234567" class="hover:text-indigo-700">+38 (044) 123-45-67</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 text-indigo-600">✉️</span>
                        <a href="mailto:support@auctiohub.test" class="hover:text-indigo-700">support@auctiohub.test</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 text-indigo-600">🕐</span>
                        <span>{{ __('messages.contact.hours') }}</span>
                    </li>
                </ul>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <h2 class="mb-3 text-base font-semibold text-gray-900">{{ __('messages.contact.social_title') }}</h2>
                <div class="flex gap-3">
                    <a href="#" class="rounded border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:border-indigo-400 hover:text-indigo-700">Telegram</a>
                    <a href="#" class="rounded border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:border-indigo-400 hover:text-indigo-700">Instagram</a>
                    <a href="#" class="rounded border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:border-indigo-400 hover:text-indigo-700">Facebook</a>
                </div>
            </div>
        </div>

        {{-- Contact form --}}
        <form method="POST" action="{{ route('contact.send') }}" class="rounded-lg border border-gray-200 bg-white p-6">
            @csrf
            @if (session('success'))
                <div class="mb-4 rounded bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('messages.contact.field_name') }}</label>
                    <input id="name" name="name" type="text" value="{{ old('name', auth()->user()?->name) }}" required
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('messages.contact.field_email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email', auth()->user()?->email) }}" required
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">{{ __('messages.contact.field_message') }}</label>
                    <textarea id="message" name="message" rows="5" required
                              class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    {{ __('messages.contact.submit') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
