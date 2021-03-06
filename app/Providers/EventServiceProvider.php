<?php

namespace App\Providers;

use App\Events\UpdateUser;
use App\Jobs\DeleteToken;
use App\Jobs\UpdateLastLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            UpdateLastLogin::class,
        ],
        Logout::class => [
            DeleteToken::class,
        ],
        UpdateUser::class => [
            \App\Listeners\SendUserUpdateEmailListener::class,
        ],
    ];
}
