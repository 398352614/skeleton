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
    "type": "out-warehouse",
    "data": {
        "tour_no": "TOUR00060095",
        "line_name": "Utrecht（3）",
        "execution_date": "2020-08-26",
        "driver_id": 34,
        "driver_name": "erkem",
        "driver_phone": "0628711510",
        "car_id": 28,
        "car_no": "V-574-LJ",
        "expect_distance": 253971,
        "expect_time": 32833,
        "merchant_id": 8,
        "batch_list": [
            {
                "batch_no": "BATCH00063274",
                "tour_no": "TOUR00060095",
                "status": 6,
                "cancel_remark": "",
                "receiver_fullname": "Tony",
                "receiver_phone": "0031642788180",
                "receiver_country": "NL",
                "receiver_post_code": " 3825GP",
                "receiver_house_number": "152",
                "receiver_city": "Amersfoort",
                "receiver_street": "Laakboulevard",
                "receiver_address": "NL",
                "expect_arrive_time": "2020-08-26 05:10:23",
                "expect_distance": 68269,
                "expect_time": 5508,
                "signature": "",
                "pay_type": 1,
                "pay_picture": "",
                "auth_fullname": "",
                "auth_birth_date": null,
                "merchant_id": "8",
                "order_list": [
                    {
                        "merchant_id": 8,
                        "order_no": "TMS0006003332",
                        "batch_no": "BATCH00063274",
                        "tour_no": "TOUR00060095",
                        "out_order_no": "MES20914323036",
                        "status": 6,
                        "package_list": [
                            {
                                "order_no": "TMS0006003332",
                                "express_first_no": "PPD31Z023491",
                                "status": 6,
                                "status_name": "取消取派",
                                "type_name": null
                            },
                            {
                                "order_no": "TMS0006003332",
                                "express_first_no": "PPD31Z043491",
                                "status": 6,
                                "status_name": "取消取派",
                                "type_name": null
                            },
                            {
                                "order_no": "TMS0006003332",
                                "express_first_no": "PPD31Z033491",
                                "status": 6,
                                "status_name": "取消取派",
                                "type_name": null
                            }
                        ]
                    }
                ]
            }
        ]
    }
}', true);
        $res = $curl->post('https://www.myeushop.com/api/tms/subscription', $params, 3);

        dd($res);
    }
}
