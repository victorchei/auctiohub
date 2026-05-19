@props(['end', 'class' => ''])

<div x-data="{
        end: new Date('{{ $end->toIso8601String() }}').getTime(),
        now: Date.now(),
        labelD: '{{ __('messages.lot.days') }}',
        labelH: '{{ __('messages.lot.hours') }}',
        labelM: '{{ __('messages.lot.minutes') }}',
        labelS: '{{ __('messages.lot.seconds') }}',
        get diff() { return Math.max(0, this.end - this.now); },
        get days() { return Math.floor(this.diff / 86400000); },
        get hours() { return Math.floor((this.diff % 86400000) / 3600000); },
        get minutes() { return Math.floor((this.diff % 3600000) / 60000); },
        get seconds() { return Math.floor((this.diff % 60000) / 1000); },
        init() { setInterval(() => this.now = Date.now(), 1000); }
    }"
    {{ $attributes->merge(['class' => $class]) }}>
    <span x-show="diff > 0">
        {{ __('messages.lot.time_left') }}:
        <span x-text="days + labelD + ' ' + hours + labelH + ' ' + minutes + labelM + ' ' + seconds + labelS"></span>
    </span>
    <span x-show="diff <= 0" class="text-red-600">{{ __('messages.lot.ended') }}</span>
</div>
