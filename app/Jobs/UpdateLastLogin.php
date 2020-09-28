<?php

namespace App\Jobs;

use Illuminate\Auth\Events\Login;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateLastLogin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @param Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        $user->last_login = now();
        $user->save();
        $user->createToken('api');
    }
}
