<?php

namespace App\Jobs;

use App\Services\BaseService;
use App\Services\TransactionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderService;

    public $data;

    public $orderSource;

    /**
     * OrderStore constructor.
     * @param BaseService $orderService
     * @param $data
     * @param $orderSource
     */
    public function __construct(TransactionService $orderService, $data, $orderSource)
    {
        $this->orderService = $orderService;
        $this->data = $data;
        $this->orderSource = $orderSource;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->orderService->store($this->data, $this->orderSource);
    }
}
