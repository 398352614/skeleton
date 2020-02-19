<?php

namespace App\Listeners;

use App\Events\Interfaces\CanCreateTourDriverEvent;
use App\Models\TourDriverEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateTourDriverEvent implements ShouldQueue
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
     * @param  CanCreateTourDriverEvent  $event
     * @return void
     */
    public function handle(CanCreateTourDriverEvent $event)
    {
        TourDriverEvent::create([
            'content'   => $event->getContent(),
            'tour_no'   => $event->getTourNo(),
            'lat'       =>  $event->getLocation()['lat'],
            'lon'       =>  $event->getLocation()['lon'],
        ]);
    }
}
