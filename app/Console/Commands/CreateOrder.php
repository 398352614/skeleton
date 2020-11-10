<?php

namespace App\Console\Commands;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Models\Merchant;
use App\Models\ReceiverAddress;
use App\Models\SenderAddress;
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
    }

    /**
     * 获取收件人信息
     * @return mixed
     */
    private function getReceiver()
    {
        $count = ReceiverAddress::query()->count();
        $id = rand(1, $count);
        $address = ReceiverAddress::query()->where('id', $id)->first();
        if (empty($address)) {
            $address = [
                'receiver_fullname' => '七娃',
                'receiver_phone' => '1234567897',
                'receiver_country' => 'NL',
                'receiver_post_code' => '1183GT',
                'receiver_house_number' => '11',
                'receiver_city' => 'Amstelveen',
                'receiver_street' => 'Straat van Gibraltar',
                'receiver_address' => '七娃家',
                'lon' => '4.87510019',
                'lat' => '52.31153083'
            ];
        } else {
            $address = $address->toArray();
        }
        $data = Arr::only($address, ['receiver_fullname', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address', 'lon', 'lat']);
        return $data;
    }

    /**
     * 获取发件人信息
     * @return array
     */
    private function getSender()
    {
        $count = SenderAddress::query()->count();
        $id = rand(1, $count);
        $address = SenderAddress::query()->where('id', $id)->first();
        if (empty($address)) {
            $address = [
                'sender_fullname' => 'test',
                'sender_phone' => '123456789',
                'sender_country' => 'NL',
                'sender_post_code' => '7041AH',
                'sender_house_number' => '23-33',
                'sender_city' => 's-Heerenberg',
                'sender_street' => 'Marktstraat',
                'sender_address' => 'test家',
            ];
        } else {
            $address = $address->toArray();
        }
        $data = Arr::only($address, ['receiver_fullname', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address', 'lon', 'lat']);
        return $data;
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
                'feature_logo' => Arr::random(['常温', '冰冻', '风房', '打折村', '海鲜预售']),
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
