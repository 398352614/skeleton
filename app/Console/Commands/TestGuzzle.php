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
        // https://dev-tms.nle-tech.com/api/test/push-test
        $curl = new CurlClient();
        $params = json_decode('{"type":"out-warehouse","data":{"tour_no":"4BAN01","line_name":"AMS（3）","execution_date":"2020-11-25","driver_id":77,"driver_name":"Ms.Wang","driver_phone":"18565654434","car_id":11,"car_no":"as","expect_distance":0,"expect_time":0,"merchant_id":117,"batch_list":[{"batch_no":"ZD2369","tour_no":"4BAN01","status":4,"cancel_remark":"","place_fullname":"wanglihui14","place_phone":"0031898989898","place_country":"NL","place_post_code":"1012BW","place_house_number":"19","place_city":"Amsterdam","place_street":"Koestraat","place_address":"NL Amsterdam Koestraat 19 1012BW","expect_arrive_time":null,"expect_distance":0,"expect_time":0,"signature":"","pay_type":1,"pay_picture":"","auth_fullname":"","auth_birth_date":null,"merchant_id":"117","tracking_order_list":[{"merchant_id":117,"out_order_no":"CL1NL1","order_no":"SMAAADQS0001","tracking_order_no":"YD00030000145","batch_no":"ZD2369","tour_no":"4BAN01","status":4,"package_list":[],"order_type":1,"order_status":2},{"merchant_id":117,"out_order_no":"BG1NL1","order_no":"SMAAADQT0001","tracking_order_no":"YD00030000146","batch_no":"ZD2369","tour_no":"4BAN01","status":4,"package_list":[{"name":"NLEONA1000017","order_no":"SMAAADQT0001","express_first_no":"NLEONA1000017","express_second_no":"","out_order_no":"BG1NL1","expect_quantity":1,"actual_quantity":0,"status":2,"sticker_no":"","sticker_amount":null,"delivery_amount":null,"is_auth":2,"auth_fullname":"","auth_birth_date":null,"status_name":"取派中","type_name":null}],"order_type":3,"order_status":2},{"merchant_id":117,"out_order_no":"BG1NL2","order_no":"SMAAADQU0001","tracking_order_no":"YD00030000147","batch_no":"ZD2369","tour_no":"4BAN01","status":4,"package_list":[{"name":"NLEONA1000025","order_no":"SMAAADQU0001","express_first_no":"NLEONA1000025","express_second_no":"","out_order_no":"BG1NL2","expect_quantity":1,"actual_quantity":0,"status":2,"sticker_no":"","sticker_amount":null,"delivery_amount":null,"is_auth":2,"auth_fullname":"","auth_birth_date":null,"status_name":"取派中","type_name":null}],"order_type":3,"order_status":2}]}]}}', true);
        $res = $curl->post('https://dev-nl-erp-api.nle-tech.com/api/p3/domestic_tms_partner/', $params, 1);

        dd($res);
    }
}
