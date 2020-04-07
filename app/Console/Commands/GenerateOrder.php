<?php

namespace App\Console\Commands;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Models\Merchant;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Faker\Factory;

class GenerateOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:generate 
                                            {--merchant_id= : merchant id} 
                                            {--execution_date= : execution date} 
                                            {--material_count= : material count} 
                                            {--package_count= : package count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate a new order';

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
     * Execute the console command
     *
     * @param OrderController $controller
     */
    public function handle(OrderController $controller)
    {
        $merchantId = $this->option('merchant_id') ?? 3;
        $merchant = Merchant::query()->where('id', $merchantId)->first();
        if (empty($merchant)) {
            $this->error('merchant id dose not exist');
            exit;
        }
        auth()->setUser($merchant);
        $executionDate = $this->option('execution_date') ?? date('Y-m-d');
        $paCount = $this->option('package_count') ?? 1;
        $maCount = $this->option('material_count') ?? 0;
        $data = array_merge(
            $this->base($executionDate, $merchantId),
            $this->getReceiver(),
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

    /**
     * 获取收件人信息
     * @return mixed
     */
    private function getReceiver()
    {
        $receiverList = [
            [
                'receiver' => '大娃',
                'receiver_phone' => '123456781',
                'receiver_country' => 'NL',
                'receiver_post_code' => '5611HW',
                'receiver_house_number' => '314',
                'receiver_city' => 'Eindhoven',
                'receiver_street' => 'De Regent',
                'receiver_address' => '大娃家',
                'lon' => '5.47409706',
                'lat' => '51.43842145'
            ],
            [
                'receiver' => '二娃',
                'receiver_phone' => '1234567892',
                'receiver_country' => 'NL',
                'receiver_post_code' => '3031AT',
                'receiver_house_number' => '199',
                'receiver_city' => 'Rotterdam',
                'receiver_street' => 'Jonker Fransstraat',
                'receiver_address' => '二娃家',
                'lon' => '4.4862111',
                'lat' => '51.92512668'
            ],
            [
                'receiver' => '三娃',
                'receiver_phone' => '1234567893',
                'receiver_country' => 'NL',
                'receiver_post_code' => '6702AG',
                'receiver_house_number' => '81',
                'receiver_city' => 'Wageningen',
                'receiver_street' => 'Troelstraweg',
                'receiver_address' => '三娃家',
                'lon' => '5.65477093',
                'lat' => '51.96484667'
            ],
            [
                'receiver' => '四娃',
                'receiver_phone' => '1234567894',
                'receiver_country' => 'NL',
                'receiver_post_code' => '1013BD',
                'receiver_house_number' => '916D',
                'receiver_city' => 'Amsterdam',
                'receiver_street' => 'Haparandaweg',
                'receiver_address' => '四娃家',
                'lon' => '4.87800829',
                'lat' => '52.39512152'
            ],
            [
                'receiver' => '五娃',
                'receiver_phone' => '1234567895',
                'receiver_country' => 'NL',
                'receiver_post_code' => '9405PR',
                'receiver_house_number' => '2',
                'receiver_city' => 'Assen',
                'receiver_street' => 'Transportweg',
                'receiver_address' => '五娃家',
                'lon' => '6.52054721',
                'lat' => '52.99499546'
            ],
            [
                'receiver' => '六娃',
                'receiver_phone' => '1234567896',
                'receiver_country' => 'NL',
                'receiver_post_code' => '9723ZB',
                'receiver_house_number' => '20',
                'receiver_city' => 'Groningen',
                'receiver_street' => 'De Zaayer',
                'receiver_address' => '六娃家',
                'lon' => '6.58270309',
                'lat' => '53.2082316'
            ],
            [
                'receiver' => '七娃',
                'receiver_phone' => '1234567897',
                'receiver_country' => 'NL',
                'receiver_post_code' => '1183GT',
                'receiver_house_number' => '11',
                'receiver_city' => 'Amstelveen',
                'receiver_street' => 'Straat van Gibraltar',
                'receiver_address' => '七娃家',
                'lon' => '4.87510019',
                'lat' => '52.31153083'
            ]
        ];
        return Arr::random($receiverList);
    }

    /**
     * 获取发件人信息
     * @return array
     */
    private function getSender()
    {
        return [
            'sender' => 'test',
            'sender_phone' => '123456789',
            'sender_country' => 'NL',
            'sender_post_code' => '7041AH',
            'sender_house_number' => '23-33',
            'sender_city' => 's-Heerenberg',
            'sender_street' => 'Marktstraat',
            'sender_address' => 'test家',
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
                'name' => $faker->word . $j,
                'express_first_no' => 'FIRST' . $faker->randomNumber(6, true) . $j,
                'express_second_no' => 'SECOND' . $faker->randomNumber(8, true) . $j,
                'out_order_no' => 'OUT' . $faker->randomNumber(6, true) . $j,
                'weight' => $faker->randomFloat(2, 0, 100),
                'quantity' => 1,
                'remark' => $faker->sentences(3, true)];
        }
        for ($k = 0; $k < $maCount; $k++) {
            $materialList[$k] = [
                "name" => $faker->word . $k,
                "code" => 'CODE' . $faker->randomNumber(8, true) . $k,
                "out_order_no" => 'OUT' . $faker->randomNumber(8, true) . $k,
                "expect_quantity" => $faker->randomNumber(2, false),
                "remark" => $faker->sentences(3, true)];
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
            'type' => $faker->numberBetween(1, 2),
            'settlement_type' => $faker->numberBetween(1, 2),
            'special_remark' => $faker->sentence(5, true),
            'remark' => $faker->sentence(5, true),
            'execution_date' => $executionDate,
            'merchant_id' => $merchantId
        ];
        return $base;
    }
}
