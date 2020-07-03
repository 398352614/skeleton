<?php

namespace App\Jobs;

use App\Models\Batch;
use App\Models\Material;
use App\Models\Package;
use App\Services\Admin\TourService;
use App\Traits\FactoryInstanceTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use WebSocket\Client;

class UpdateLineCountTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * 任务连接名称。
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务发送到的队列的名称.
     *
     * @var string|null
     */
    public $queue = 'update-line-time';


    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 30;


    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    public $tour_no;


    /**
     * UpdateLineCountTime constructor.
     * @param $tourNo
     */
    public function __construct($tourNo)
    {
        $this->tour_no = $tourNo;
    }


    /**
     * Execute the job.
     * @throws \WebSocket\BadOpcodeException
     */
    public function handle()
    {
        try {
            /**@var TourService $tourService */
            $tourService = FactoryInstanceTrait::getInstance(TourService::class);
            $batchList = Batch::query()->where('tour_no', $this->tour_no)->get(['id', 'sort_id'])->toArray();
            $sortBatch = Arr::first($batchList, function ($batch) {
                return $batch['sort_id'] != 1000;
            });
            if (!empty($sortBatch)) {
                $batchList = Arr::sort($batchList, function ($batch) {
                    return $batch['sort_id'];
                });
                $tourService->updateBatchIndex(['tour_no' => $this->tour_no, 'batch_ids' => array_column($batchList, 'id')]);
            } else {
                $tourService->autoOpTour(['tour_no' => $this->tour_no]);
            }
        } catch (\Exception $ex) {
            Log::channel('job-daily')->error('智能调度错误:' . $ex->getMessage());
            return false;
        }
        return true;
    }
}
