<?php

namespace App\Jobs;

use App\Events\TourNotify\NextBatch;
use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Material;
use App\Models\Package;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\Driver\TourService;
use App\Traits\CompanyTrait;
use App\Traits\FactoryInstanceTrait;
use App\Traits\TourTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use WebSocket\Client;

class UpdateTour implements ShouldQueue
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
    public $queue = 'update-tour';

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

    private $batch_ids;


    /**
     * UpdateLineCountTime constructor.
     * @param $tourNo
     * @param $batchIds
     */
    public function __construct($tourNo, $batchIds)
    {
        $this->tour_no = $tourNo;
        $this->batch_ids = $batchIds;
    }


    /**
     *
     */
    public function handle()
    {
        try {
            $tour = DB::table('tour')->where('tour_no', $this->tour_no)->first();
            $company = CompanyTrait::getCompany($tour->company_id);
            request()->headers->set('X-Uuid', $company['company_code']);
            $tourService = FactoryInstanceTrait::getInstance(TourService::class);
            $tourService->updateBatchIndex(['tour_no' => $this->tour_no, 'batch_ids' => $this->batch_ids]);
        } catch (BusinessLogicException $e) {
            Log::info('更新线路失败:' . $e->getMessage());
        } catch (\Exception $e) {
            Log::info('程序错误:' . $e->getMessage());
        }
    }
}
