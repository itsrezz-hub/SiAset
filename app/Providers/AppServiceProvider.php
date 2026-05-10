<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

   public function boot(): void
{
    // Logika pengecekan Role Admin atau Super Admin
    Gate::define('admin-access', function ($user) {
        return $user->role === 'admin' || $user->role === 'superadmin';
    });
}
}