<?php

namespace App\Providers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
{
    View::composer(['layouts.navigation', 'layouts.notification-modal'], function ($view) {

        $userId = Auth::id();

        if ($userId) {

            // === BADGE COUNT (notif yang belum dibaca & belum dihapus user) ===
            $notifCount = ActivityLog::where(function ($q) use ($userId) {
                    $q->whereNull('read_by')
                      ->orWhereJsonDoesntContain('read_by', $userId);
                })
                ->where(function ($q) use ($userId) {
                    $q->whereNull('deleted_by')
                      ->orWhereJsonDoesntContain('deleted_by', $userId);
                })
                ->count();

            // === LIST NOTIF (hanya notif yang tidak dihapus user) ===
            $notifAll = ActivityLog::where(function ($q) use ($userId) {
                    $q->whereNull('deleted_by')
                      ->orWhereJsonDoesntContain('deleted_by', $userId);
                })
                ->orderBy('id', 'desc')
                ->get();

        } else {

            // Jika belum login
            $notifCount = 0;
            $notifAll = collect();
        }

        // Kirim ke blade
        $view->with('notifCount', $notifCount)
             ->with('notifAll', $notifAll);
    });
}
}
