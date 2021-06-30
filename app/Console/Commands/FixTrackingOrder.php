<?php

namespace App\Console\Commands;

use App\Exceptions\BusinessLogicException;
use App\Services\Admin\TrackingOrderService;
use App\Services\BaseConstService;
use App\Traits\AlphaTrait;
use App\Traits\FactoryInstanceTrait;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixTrackingOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:tracking-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix tracking order table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->info('fix begin');
            \Illuminate\Support\Facades\Schema::dropIfExists('old_tracking_order');
            \Illuminate\Support\Facades\Schema::create('old_tracking_order', function (Blueprint $table) {
                $table->integerIncrements('id');
                $table->integer('company_id')->default(null)->nullable()->comment('公司ID');
                $table->integer('merchant_id')->default(null)->nullable()->comment('货主ID');
                $table->integer('out_user_id')->default(null)->nullable()->comment('客户单号');
                $table->string('out_order_no', 50)->default('')->nullable()->comment('货号');
                $table->string('order_no', 50)->default('')->nullable()->comment('订单号');
                $table->string('tracking_order_no', 50)->default('')->nullable()->comment('运单号');
                $table->string('batch_no', 50)->default('')->nullable()->comment('站点编号');
                $table->string('tour_no', 50)->default('')->nullable()->comment('线路任务编号');
                $table->tinyInteger('type')->default(1)->nullable()->comment('运单类型1-取2-派');
                $table->date('execution_date')->default(null)->nullable()->comment('取派日期');
                $table->string('warehouse_fullname', 50)->default('')->nullable()->comment('发件人姓名');
                $table->string('warehouse_phone', 50)->default('')->nullable()->comment('发件人手机号码');
                $table->string('warehouse_country', 50)->default('')->nullable()->comment('发件人国家');
                $table->string('warehouse_post_code', 50)->default('')->nullable()->comment('发件人邮编');
                $table->string('warehouse_house_number', 50)->default('')->nullable()->comment('发件人门牌号');
                $table->string('warehouse_city', 50)->default('')->nullable()->comment('发件人城市');
                $table->string('warehouse_street', 50)->default('')->nullable()->comment('发件人街道');
                $table->string('warehouse_address', 50)->default('')->nullable()->comment('发件人地址');
                $table->string('warehouse_lon', 50)->default('')->nullable()->comment('收件人经度');
                $table->string('warehouse_lat', 50)->default('')->nullable()->comment('收件人纬度');
                $table->string('place_fullname', 50)->default('')->nullable()->comment('收件人姓名');
                $table->string('place_phone', 50)->default('')->nullable()->comment('收件人手机号码');
                $table->string('place_country', 50)->default('')->nullable()->comment('收件人国家');
                $table->string('place_post_code', 50)->default('')->nullable()->comment('收件人邮编');
                $table->string('place_house_number', 50)->default('')->nullable()->comment('收件人门牌号');
                $table->string('place_city', 50)->default('')->nullable()->comment('收件人城市');
                $table->string('place_street', 50)->default('')->nullable()->comment('收件人街道');
                $table->string('place_address', 50)->default('')->nullable()->comment('收件人地址');
                $table->string('place_lon', 50)->default('')->nullable()->comment('收件人经度');
                $table->string('place_lat', 50)->default('')->nullable()->comment('收件人纬度');
                $table->integer('driver_id')->default(null)->nullable()->comment('司机ID');
                $table->string('driver_name', 50)->default('')->nullable()->comment('司机姓名');
                $table->string('driver_phone', 50)->default('')->nullable()->comment('司机电话');
                $table->integer('car_id')->default(null)->nullable()->comment('车辆ID');
                $table->string('car_no', 50)->default('')->nullable()->comment('车牌号');
                $table->smallInteger('status')->default(1)->nullable()->comment('运单状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站');
                $table->smallInteger('out_status')->default(1)->nullable()->comment('是否可出库:1-是2-否');
                $table->tinyInteger('exception_label')->default(1)->nullable()->comment('标签1-正常2-异常');
                $table->smallInteger('cancel_type')->default(null)->nullable()->comment('取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他');
                $table->string('cancel_remark', 50)->default('')->nullable()->comment('取消取派-具体内容');
                $table->string('cancel_picture', 50)->default('')->nullable()->comment('取消取派-图片');
                $table->string('mask_code')->default('')->nullable()->comment('掩码');
                $table->string('special_remark')->default('')->nullable()->comment('特殊事项');
                $table->dateTime('created_at')->default(null)->nullable()->comment('创建时间');
                $table->dateTime('updated_at')->default(null)->nullable()->comment('修改时间');
                $table->unique('tracking_order_no', 'tracking_order_no');
                $table->index('company_id', 'company_id');
                $table->index('merchant_id', 'merchant_id');
                $table->index('execution_date', 'execution_date');
                $table->index('order_no', 'order_no');
                $table->index('batch_no', 'batch_no');
                $table->index('tour_no', 'tour_no');
                $table->index('status', 'status');
            });
            $date = now();
            $cellData = [];
            $companyList = DB::table('order')->pluck('company_id')->toArray();
            foreach ($companyList as $k => $v) {
                $orderList = DB::table('order')->where('company_id', $v)->get();
                foreach ($orderList as $x => $y) {
                    $y = collect($y)->toArray();
                    $cellData[] = [
                        'tracking_order_no' => $this->createTrackingOrderNo($y['company_id']),
                        'company_id' => $y['company_id'],
                        'merchant_id' => $y['merchant_id'],
                        'out_user_id' => $y['out_user_id'],
                        'out_order_no' => $y['out_order_no'],
                        'batch_no' => $y['batch_no'],
                        'tour_no' => $y['tour_no'],
                        'type' => $y['type'],
                        'execution_date' => $y['execution_date'],
                        'warehouse_fullname' => $y['sender_fullname'],
                        'warehouse_phone' => $y['sender_phone'],
                        'warehouse_country' => $y['sender_country'],
                        'warehouse_post_code' => $y['sender_post_code'],
                        'warehouse_house_number' => $y['sender_house_number'],
                        'warehouse_city' => $y['sender_city'],
                        'warehouse_street' => $y['sender_street'],
                        'warehouse_address' => $y['sender_address'],
                        'warehouse_lon' => '',
                        'warehouse_lat' => '',
                        'place_fullname' => $y['receiver_fullname'],
                        'place_phone' => $y['receiver_phone'],
                        'place_country' => $y['receiver_country'],
                        'place_post_code' => $y['receiver_post_code'],
                        'place_house_number' => $y['receiver_house_number'],
                        'place_city' => $y['receiver_city'],
                        'place_address' => $y['receiver_address'],
                        'place_street' => $y['receiver_street'],
                        'place_lon' => $y['lon'],
                        'place_lat' => $y['lat'],
                        'driver_id' => $y['driver_id'],
                        'driver_name' => $y['driver_name'],
                        'driver_phone' => $y['driver_phone'],
                        'car_id' => $y['car_id'],
                        'car_no' => $y['car_no'],
                        'status' => $y['status'],
                        'out_status' => $y['out_status'],
                        'exception_label' => $y['exception_label'],
                        'cancel_type' => $y['cancel_type'],
                        'cancel_remark' => $y['cancel_remark'],
                        'cancel_picture' => $y['cancel_picture'],
                        'mask_code' => $y['mask_code'],
                        'special_remark' => $y['special_remark'],
                        'created_at' => (string)$date,
                        'updated_at' => (string)$date,
                    ];
                }
                $cellData = collect($cellData)->chunk(100)->toArray();
                for($i=0,$j=count($cellData);$i<$j;$i++){
                    DB::table('old_tracking_order')->insert($cellData[$i]);
                }
            }
        } catch (\Exception $e) {
            $this->info('fix fail:' . $e);
        }
        $this->info('fix end');
    }

    /**
     * 创建运单编号
     * @param $params
     * @return string
     * @throws BusinessLogicException
     */
    public function createTrackingOrderNo($params)
    {
        $info = DB::table('order_no_rule')->where('company_id', $params)->where('type', BaseConstService::TRACKING_ORDER_NO_TYPE)->where('status', BaseConstService::ON)->first();
        $info = collect($info)->toArray();
        $trackingOrderNo = $info['prefix'] . $info['start_string_index'] . sprintf("%0{$info['int_length']}s", $info['start_index']);
        //修改索引
        $startStringIndex = !empty($info['start_string_index']) ? AlphaTrait::getNextString($info['start_string_index']) : '';
        $index = ($startStringIndex === str_repeat('A', $info['string_length'])) ? $info['start_index'] + 1 : $info['start_index'];
        DB::table('order_no_rule')->where('id', $info['id'])->update(['start_index' => $index, 'start_string_index' => $startStringIndex]);
        return $trackingOrderNo;
    }
}
