<?php
use Illuminate\Auth\Events\Login;
use App\Listeners\UpdateLoginStreak;

class EventServiceProvider extends ServiceProvider{
protected $listen = [
    // ...listener lain yang sudah ada, jangan dihapus...

    Login::class => [
        UpdateLoginStreak::class,
    ],
];
}