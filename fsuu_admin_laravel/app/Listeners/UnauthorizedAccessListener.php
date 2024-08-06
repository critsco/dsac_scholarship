<?php

namespace App\Listeners;

use App\Events\UnauthorizedAccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UnauthorizedAccessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UnauthorizedAccess $event)
    {
        // Handle the unauthorized access event
        // You can log the event, send notifications, etc.

        // Example: Log the event
        Log::warning('Unauthorized Access', $event->requestInfo);
    }
}
