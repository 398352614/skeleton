<?php

namespace App\Console\Commands\Data;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FillTrackingOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:tracking-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tracking Order Fill';

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
        $orderSql = "UPDATE `order` AS a SET a.`tracking_order_no`=(SELECT b.`tracking_order_no` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $packageSql = "UPDATE `package` AS a SET a.`tracking_order_no`=(SELECT b.`tracking_order_no` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $materialSql = "UPDATE `material` AS a SET a.`tracking_order_no`=(SELECT b.`tracking_order_no` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $trackingOrderPackageSql = "UPDATE `tracking_order_package` AS a SET a.`tracking_order_no`=(SELECT b.`tracking_order_no` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $trackingOrderMaterialSql = "UPDATE `tracking_order_material` AS a SET a.`tracking_order_no`=(SELECT b.`tracking_order_no` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $orderStatus1Sql = "UPDATE `order` SET `status` = 1 WHERE `status` IN (1,2,3)";
        $orderStatus2Sql = "UPDATE `order` SET `status` = 2 WHERE `status` = 4";
        $orderStatus3Sql = "UPDATE `order` SET `status` = 3 WHERE `status` = 5";
        $orderStatus4Sql = "UPDATE `order` SET `status` = 4 WHERE `status` = 6";
        $orderStatus5Sql = "UPDATE `order` SET `status` = 5 WHERE `status` = 7";
        $packageStatus1Sql = "UPDATE `package` SET `status` = 1 WHERE `status` IN (1,2,3)";
        $packageStatus2Sql = "UPDATE `package` SET `status` = 2 WHERE `status` = 4";
        $packageStatus3Sql = "UPDATE `package` SET `status` = 3 WHERE `status` = 5";
        $packageStatus4Sql = "UPDATE `package` SET `status` = 4 WHERE `status` = 6";
        $packageStatus5Sql = "UPDATE `package` SET `status` = 5 WHERE `status` = 7";

        $trackingOrderPackageExecutionDateSql = "UPDATE `tracking_order_package` AS a SET a.`execution_date`=(SELECT b.`execution_date` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $trackingOrderPackageBatchSql = "UPDATE `tracking_order_package` AS a SET a.`batch_no`=(SELECT b.`batch_no` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $trackingOrderPackageTourSql = "UPDATE `tracking_order_package` AS a SET a.`tour_no`=(SELECT b.`tour_no` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $trackingOrderPackageStatusSql = "UPDATE `tracking_order_package` AS a SET a.`status`=(SELECT b.`status` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $trackingOrderMaterialExecutionDateSql = "UPDATE `tracking_order_material` AS a SET a.`execution_date`=(SELECT b.`execution_date` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $trackingOrderMaterialBatchSql = "UPDATE `tracking_order_material` AS a SET a.`batch_no`=(SELECT b.`batch_no` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";
        $trackingOrderMaterialTourSql = "UPDATE `tracking_order_material` AS a SET a.`tour_no`=(SELECT b.`tour_no` FROM `tracking_order` AS b WHERE b.`order_no`=a.`order_no` LIMIT 1)";

//        DB::update($orderSql);
//        DB::update($packageSql);
//        DB::update($materialSql);
//        DB::update($trackingOrderPackageSql);
//        DB::update($trackingOrderMaterialSql);

//        DB::update($trackingOrderPackageExecutionDateSql);
//        DB::update($trackingOrderPackageBatchSql);
//        DB::update($trackingOrderPackageTourSql);
//        DB::update($trackingOrderPackageStatusSql);
//        DB::update($trackingOrderMaterialExecutionDateSql);
//        DB::update($trackingOrderMaterialBatchSql);
//        DB::update($trackingOrderMaterialTourSql);
        $deleteSql = DB::delete("DELETE from `tracking_order_package` WHERE `status` is null");


//        DB::update($orderStatus1Sql);
//        DB::update($orderStatus2Sql);
//        DB::update($orderStatus3Sql);
//        DB::update($orderStatus4Sql);
//        DB::update($orderStatus5Sql);

//        DB::update($packageStatus1Sql);
//        DB::update($packageStatus2Sql);
//        DB::update($packageStatus3Sql);
//        DB::update($packageStatus4Sql);
//        DB::update($packageStatus5Sql);
        return;
    }
}
