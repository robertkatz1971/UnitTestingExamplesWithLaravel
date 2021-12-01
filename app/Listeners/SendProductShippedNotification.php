<?php

namespace App\Listeners;

use App\Events\ProductShipped;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProductShippedNotification
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
     * @param  ProductShipped  $event
     * @return void
     */
    public function handle(ProductShipped $event)
    {
        Log::info('Product shipped');
    }
}
