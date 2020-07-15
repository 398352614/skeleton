<?php

namespace App\Listeners;

use App\Events\Interfaces\ITourDriver;
use App\Models\RouteTracking;
use App\Models\TourDriverEvent;

class TourDriver
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
     * @param ITourDriver $event
     * @return void
     */
    public function handle(ITourDriver $event)
    {
        $location = $event->getLocation();
        $tourDriverEvent = TourDriverEvent::create([
            'content'   => $event->getContent(),
            'tour_no'   => $event->getTourNo(),
            'lat'       =>  $location['lat'],
            'lon'       =>  $location['lon'],
            'address'   => $event->getAddress(),
            'batch_no'   => $event->getBatchNo()
        ]);
        RouteTracking::create([
            'lon' => $location['lon'],
            'lat' => $location['lat'],
            'tour_no'   => $event->getTourNo(),
            'driver_id' => $event->getDriverId(),
            'time' =>time(),
            'tour_driver_event_id'=>$tourDriverEvent->id,
        ]);
    }
}
