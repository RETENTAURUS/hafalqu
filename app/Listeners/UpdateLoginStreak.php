<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Carbon\Carbon;

class UpdateLoginStreak
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $today = Carbon::today();
        $lastLogin = $user->last_login_date ? Carbon::parse($user->last_login_date) : null;

        if ($lastLogin === null) {
            // Login pertama kali
            $user->login_streak = 1;
        } elseif ($lastLogin->isSameDay($today)) {
            // Sudah login hari ini sebelumnya, jangan hitung dobel
            return;
        } elseif ($lastLogin->isSameDay($today->copy()->subDay())) {
            // Login kemarin -> lanjutkan streak
            $user->login_streak = ($user->login_streak ?? 0) + 1;
        } else {
            // Ada hari yang terlewat -> streak reset
            $user->login_streak = 1;
        }

        $user->last_login_date = $today;
        $user->save();
    }
}