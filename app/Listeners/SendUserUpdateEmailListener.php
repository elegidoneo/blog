<?php

namespace App\Listeners;

use App\Events\UpdateUser;
use App\Notifications\UpdateUserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserUpdateEmailListener
{
    /**
     * Handle the event.
     *
     * @param  UpdateUser  $event
     * @return void
     */
    public function handle(UpdateUser $event)
    {
        $event->user->notify(new UpdateUserNotification($event->before, $event->after));
    }
}
