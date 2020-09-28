<?php

namespace App\Jobs;

use Illuminate\Auth\Events\Logout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @param Logout $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $token = $event->user->tokens();
        $token->whereId($token->first()->id)->delete();
    }
}
