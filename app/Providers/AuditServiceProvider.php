<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Lot;
use App\Models\User;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;

class AuditServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Lot::observe(AuditObserver::class);
        Category::observe(AuditObserver::class);
        User::observe(AuditObserver::class);
    }
}
