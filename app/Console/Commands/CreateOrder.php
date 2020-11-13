<?php

namespace App\Console\Commands;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Models\Merchant;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Faker\Factory;

class CreateOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:create
                                            {--times= : times}
                                            {--merchant_id= : merchant id}
                                            {--execution_date= : execution date}
                                            {--material_count= : material count}
                                            {--package_count= : package count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a new order';

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
     * @param OrderController $controller
     * @throws \Exception
     */
    public function handle(OrderController $controller)
    {
        $times = $this->option('times') ?? 1;
        for ($i = 0; $i < $times; $i++) {
            $merchantId = $this->option('merchant_id') ?? 3;
            $merchant = Merchant::query()->where('id', $merchantId)->first();
            if (empty($merchant)) {
                $this->error('merchant id dose not exist');
                exit;
            }
            auth()->setUser($merchant);
            $executionDate = $this->option('execution_date') ?? date('Y-m-d');
            $paCount = $this->option('package_count') ?? 1;
            $maCount = $this->option('material_count') ?? 1;
            $data = array_merge(
                $this->base($executionDate, $merchantId),
                $this->getPlace(),
                $this->getSender(),
                $this->getMaPaList($maCount, $paCount)
            );
            try {
                $controller->setData($data);
                $res = $controller->store();
                $this->info(json_encode($res, true));
            } catch (BusinessLogicException $exception) {
                $this->error($exception->getMessage());
                exit;
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
                exit;
            }
        }
    }

    /**
     * 获取收件人信息
     * @return mixed
     */
    private function getPlace()
    {
        $placeList = [
            [
                'place_fullname' => '大娃',
                'place_phone' => '123456781',
                'place_country' => 'NL',
                'place_post_code' => '5611HW',
                'place_house_number' => '314',
                'place_city' => 'Eindhoven',
                'place_street' => 'De Regent',
                'place_address' => '大娃家',
                'lon' => '5.47409706',
                'lat' => '51.43842145'
            ],
            [
                'place_fullname' => '二娃',
                'place_phone' => '1234567892',
                'place_country' => 'NL',
                'place_post_code' => '3031AT',
                'place_house_number' => '199',
                'place_city' => 'Rotterdam',
                'place_street' => 'Jonker Fransstraat',
                'place_address' => '二娃家',
                'lon' => '4.4862111',
                'lat' => '51.92512668'
            ],
            [
                'place_fullname' => '三娃',
                'place_phone' => '1234567893',
                'place_country' => 'NL',
                'place_post_code' => '6702AG',
                'place_house_number' => '81',
                'place_city' => 'Wageningen',
                'place_street' => 'Troelstraweg',
                'place_address' => '三娃家',
                'lon' => '5.65477093',
                'lat' => '51.96484667'
            ],
            [
                'place_fullname' => '四娃',
                'place_phone' => '1234567894',
                'place_country' => 'NL',
                'place_post_code' => '1013BD',
                'place_house_number' => '916D',
                'place_city' => 'Amsterdam',
                'place_street' => 'Haparandaweg',
                'place_address' => '四娃家',
                'lon' => '4.87800829',
                'lat' => '52.39512152'
            ],
            [
                'place_fullname' => '五娃',
                'place_phone' => '1234567895',
                'place_country' => 'NL',
                'place_post_code' => '9405PR',
                'place_house_number' => '2',
                'place_city' => 'Assen',
                'place_street' => 'Transportweg',
                'place_address' => '五娃家',
                'lon' => '6.52054721',
                'lat' => '52.99499546'
            ],
            [
                'place_fullname' => '六娃',
                'place_phone' => '1234567896',
                'place_country' => 'NL',
                'place_post_code' => '9723ZB',
                'place_house_number' => '20',
                'place_city' => 'Groningen',
                'place_street' => 'De Zaayer',
                'place_address' => '六娃家',
                'lon' => '6.58270309',
                'lat' => '53.2082316'
            ],
            [
                'place_fullname' => '七娃',
                'place_phone' => '1234567897',
                'place_country' => 'NL',
                'place_post_code' => '1183GT',
                'place_house_number' => '11',
                'place_city' => 'Amstelveen',
                'place_street' => 'Straat van Gibraltar',
                'place_address' => '七娃家',
                'lon' => '4.87510019',
                'lat' => '52.31153083'
            ]
        ];
        return Arr::random($placeList);
    }

    /**
     * 获取发件人信息
     * @return array
     */
    private function getSender()
    {
        return [
            'second_place_fullname' => 'test',
            'second_place_phone' => '123456789',
            'second_place_country' => 'NL',
            'second_place_post_code' => '7041AH',
            'second_place_house_number' => '23-33',
            'second_place_city' => 's-Heerenberg',
            'second_place_street' => 'Marktstraat',
            'second_place_address' => 'test家',
        ];
    }


    /**
     * 获取材料列表和包裹列表
     *
     * @param int $maCount
     * @param int $paCount
     * @return array
     */
    private function getMaPaList($maCount = 0, $paCount = 0)
    {
        $faker = Factory::create('nl-NL');
        $packageList = [];
        $materialList = [];
        for ($j = 0; $j < $paCount; $j++) {
            $packageList[$j] = [
                'name' => '',
                'express_first_no' => $j . 'F' . $faker->randomNumber(5, true),
                'express_second_no' => $j . 'S' . $faker->randomNumber(5, true),
                'out_order_no' => $j . 'O' . $faker->randomNumber(5, true),
                'weight' => $faker->randomFloat(2, 0, 100),
                'expect_quantity' => 1,
                'feature_logo' => Arr::random(['常温','冰冻','风房']),
                'remark' => $faker->sentences(1, true)];
        }
        for ($k = 0; $k < $maCount; $k++) {
            $materialList[$k] = [
                "name" => $faker->word . $k,
                "code" => $k . 'C' . $faker->randomNumber(5, true),
                "out_order_no" => $k . 'O' . $faker->randomNumber(5, true),
                "expect_quantity" => $faker->randomNumber(2, false),
                "remark" => $faker->sentences(1, true)];
        }
        return ['material_list' => $materialList, 'package_list' => $packageList];
    }

    /**
     * 获取基础数据
     * @param $executionDate
     * @return array
     */
    public function base($executionDate, $merchantId)
    {
        $faker = Factory::create('nl-NL');
        $base = [
            'type' => Arr::random([1, 2]),
            'settlement_type' => Arr::random([1, 2]),
            'special_remark' => $faker->sentence(2, true),
            'remark' => $faker->sentence(2, true),
            'execution_date' => $executionDate,
            'merchant_id' => $merchantId
        ];
        return $base;
    }
}
