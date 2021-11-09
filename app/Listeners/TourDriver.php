<?php

namespace App\Listeners;

use App\Events\Interfaces\ITourDriver;
use App\Jobs\AddData;

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
        $now = now();
        $data = [
            'company_id' => auth()->user()->company_id,
            'content' => $event->getContent(),
            'tour_no' => $event->getTourNo(),
            'lat' => $location['lat'],
            'lon' => $location['lon'],
            'address' => $event->getAddress(),
            'batch_no' => $event->getBatchNo(),
            'created_at' => $now,
            'updated_at' => $now
        ];
        dispatch(new AddData('tour-event', $data));
    }
}
