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
        "tour_no": "TOUR00060206",
        "line_name": "Eindhoven（3）",
        "execution_date": "2020-09-02",
        "driver_id": 25,
        "driver_name": "yili",
        "driver_phone": "0628400798",
        "car_id": 20,
        "car_no": "V-900-PF",
        "expect_distance": 448862,
        "expect_time": 42227,
        "merchant_id": 8,
        "batch": {
            "batch_no": "BATCH00064398",
            "tour_no": "TOUR00060206",
            "status": 5,
            "cancel_remark": "",
            "receiver_fullname": "Bo Liu",
            "receiver_phone": "0031615212442",
            "receiver_country": "NL",
            "receiver_post_code": "5582AS",
            "receiver_house_number": "5",
            "receiver_city": "Waalre",
            "receiver_street": "Bosranklaan",
            "receiver_address": "NL Kaatsheuvel Burgemeester van Besouwlaan 7 5171JS",
            "expect_arrive_time": "2020-09-02 15:39:35",
            "expect_distance": 7285,
            "expect_time": 630,
            "signature": "https://tms.eutechne.com/storage/driver/images/6/2020-09-02/25/tour/202009021542195f4fa13b47ab1.png",
            "pay_type": 4,
            "pay_picture": "",
            "auth_fullname": "🈶",
            "auth_birth_date": "2000-02-01",
            "delivery_count": 0,
            "merchant_id": 8,
            "order_list": [
                {
                    "merchant_id": 8,
                    "order_no": "TMS0006004464",
                    "batch_no": "BATCH00065281",
                    "tour_no": "TOUR00060206",
                    "out_order_no": "MES20910431007",
                    "status": 5,
                    "package_list": [
                        {
                            "name": "PPD34Z03M495",
                            "order_no": "TMS0006004464",
                            "express_first_no": "PPD34Z03M495",
                            "express_second_no": "",
                            "out_order_no": "PPD34Z03M495",
                            "expect_quantity": 1,
                            "actual_quantity": 1,
                            "status": 5,
                            "sticker_no": "",
                            "sticker_amount": "0.00",
                            "delivery_amount": "0.00",
                            "is_auth": 1,
                            "auth_fullname": "🈶",
                            "auth_birth_date": "2000-02-01",
                            "status_name": "已完成",
                            "type_name": null,
                            "delivery_count": 0
                        },
                        {
                            "name": "PPD34Z02M495",
                            "order_no": "TMS0006004464",
                            "express_first_no": "PPD34Z02M495",
                            "express_second_no": "",
                            "out_order_no": "PPD34Z02M495",
                            "expect_quantity": 1,
                            "actual_quantity": 1,
                            "status": 5,
                            "sticker_no": "",
                            "sticker_amount": "0.00",
                            "delivery_amount": "0.00",
                            "is_auth": 1,
                            "auth_fullname": "🈶",
                            "auth_birth_date": "2000-02-01",
                            "status_name": "已完成",
                            "type_name": null,
                            "delivery_count": 0
                        },
                        {
                            "name": "PPD34Z04M495",
                            "order_no": "TMS0006004464",
                            "express_first_no": "PPD34Z04M495",
                            "express_second_no": "",
                            "out_order_no": "PPD34Z04M495",
                            "expect_quantity": 1,
                            "actual_quantity": 1,
                            "status": 5,
                            "sticker_no": "",
                            "sticker_amount": "0.00",
                            "delivery_amount": "0.00",
                            "is_auth": 1,
                            "auth_fullname": "🈶",
                            "auth_birth_date": "2000-02-01",
                            "status_name": "已完成",
                            "type_name": null,
                            "delivery_count": 0
                        }
                    ],
                    "material_list": [],
                    "delivery_count": 0
                }
            ]
        },
        "additional_package_list": []
    }
}', true);
        $res = $curl->post('https://www.myeushop.com/api/tms/subscription', $params, 2);

        dd($res);
    }
}
