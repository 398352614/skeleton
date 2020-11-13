<?php

namespace App\Console\Commands;

use App\Services\CurlClient;
use Illuminate\Console\Command;

class TestGuzzle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:guzzle {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试 guzzle';

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
        $url = $this->argument('url');
        // $url = 'https://dev-distancematrix.nle-tech.com';
        // $url = 'https://dev-distancematrix.nle-tech.com/api/update-line?api_key=nletech&timestamp=1584347717&sign=a02822e91d08baba93f08e8cb63ae560a226e6b98c5a75b56d125a23d18c9e0e';
        // $url = 'https://www.baidu.com';

        $curl = new CurlClient();
        $params = json_decode('
        {
    "type": "assign-batch",
    "data": {
        "tour_no": "TOUR00060073",
        "line_name": "Enchede（6）",
        "execution_date": "2020-08-22",
        "driver_id": 24,
        "driver_name": "ali",
        "driver_phone": "0615277686",
        "car_id": 25,
        "car_no": "V-441-RJ",
        "expect_distance": 16110,
        "expect_time": 1203,
        "merchant_id": 8,
        "batch": {
            "batch_no": "BATCH00061110",
            "tour_no": "TOUR00060073",
            "status": 5,
            "cancel_remark": "",
            "place_fullname": "L zheng",
            "place_phone": "0031633085585",
            "place_country": "NL",
            "place_post_code": "7441CN",
            "place_house_number": "90",
            "place_city": "Nijverdal",
            "place_street": "Veldsweg",
            "place_address": "NL Nijverdal Veldsweg 90 7441CN",
            "expect_arrive_time": "2020-08-22 12:34:09",
            "expect_distance": 113530,
            "expect_time": 4532,
            "signature": "https://tms.eutechne.com/storage/driver/images/6/2020-08-22/24/tour/202008222201125f417988056b4.png",
            "pay_type": 4,
            "pay_picture": "",
            "auth_fullname": "",
            "auth_birth_date": null,
            "delivery_count": 0,
            "merchant_id": 8,
            "order_list": [
                {
                    "merchant_id": 8,
                    "order_no": "TMS0006001123",
                    "batch_no": "BATCH00061110",
                    "tour_no": "TOUR00060073",
                    "out_order_no": "341282",
                    "status": 5,
                    "package_list": [
                        {
                            "name": "PEHH9098250003",
                            "order_no": "TMS0006001606",
                            "express_first_no": "EAXOND230188",
                            "express_second_no": "",
                            "out_order_no": "MES20904584043",
                            "expect_quantity": 1,
                            "actual_quantity": 1,
                            "status": 5,
                            "sticker_no": "",
                            "sticker_amount": "0.00",
                            "delivery_amount": "0.00",
                            "is_auth": 2,
                            "auth_fullname": "",
                            "auth_birth_date": null,
                            "status_name": "已完成",
                            "type_name": null,
                            "delivery_count": 0
                        }
                    ],
                    "material_list": [],
                    "delivery_count": 0
                }
            ]
        }
    }
}', true);
        $res = $curl->post('https://www.myeushop.com/api/tms/subscription', $params, 3);

        dd($res);
    }
}
