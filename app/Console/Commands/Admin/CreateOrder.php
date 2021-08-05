<?php

namespace App\Console\Command\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Models\Address;
use App\Models\Merchant;
use Carbon\Carbon;
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
    protected $signature = 'create:order
                                            {--times= : times}
                                            {--type= : type}
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
        if (config('tms.app_env') == 'local') {
            $times = $this->option('times') ?? 1;
            for ($i = 0; $i < $times; $i++) {
                $merchantId = $this->option('merchant_id') ?? 3;
                $merchant = Merchant::query()->where('id', $merchantId)->first();
                if (empty($merchant)) {
                    $this->error('merchant id dose not exist');
                    exit;
                }
                auth()->setUser($merchant);
                $paCount = $this->option('package_count') ?? 0;
                $maCount = $this->option('material_count') ?? 1;
                $data = array_merge($this->getMaPaList($maCount, $paCount), $this->base($merchantId));
                $data['type'] = $this->option('type') ?? Arr::random([1, 2, 3]);
                $data['execution_date'] = $this->option('execution_date') ?? date('Y-m-d');
                if ($data['type'] == 3) {
                    $data = array_merge($data, $this->getAddress(), $this->getSecondAddress());
                    $data['second_execution_date'] = Carbon::create($data['execution_date'])->addDay()->format('Y-m-d');
                } else {
                    $data = array_merge($data, $this->getAddress());
                }
                try {
                    $controller->setData($data);
                    $res = $controller->store();
                    $this->info(json_encode($res, true));
                } catch (BusinessLogicException $exception) {
                    $this->error($exception->getMessage());
                    continue;
                } catch (\Exception $exception) {
                    $this->error($exception->getMessage());
                    continue;
                }
            }
        }
    }

    /**
     * 获取地址信息
     * @param $executionDate
     * @return mixed
     */
    private function getAddress()
    {
        $count = Address::query()->count();
        $id = rand(1, $count);
        $address = Address::query()->get()[$id];
        if (empty($address)) {
            $address = [
                'place_fullname' => '七娃',
                'place_phone' => '1234567897',
                'place_country' => 'NL',
                'place_post_code' => '1183GT',
                'place_house_number' => '11',
                'place_city' => 'Amstelveen',
                'place_street' => 'Straat van Gibraltar',
                'place_address' => '七娃家',
                'place_lon' => '4.87510019',
                'place_lat' => '52.31153083',
            ];
        } else {
            $address = $address->toArray();
        }
        return Arr::only($address, ['place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address', 'place_lon', 'place_lat']);
    }

    /**
     * 获取发件人信息
     * @param $executionDate
     * @return array
     */
    private function getSecondAddress()
    {
        $newData = [];
        $data = $this->getAddress();
        foreach ($data as $k => $v) {
            $newData['second_' . $k] = $v;
        }
        return $newData;
    }


    /**
     * 获取材料列表和包裹列表
     *
     * @param int $maCount
     * @param int $paCount
     * @return array
     */
    private function getMaPaList($maCount, $paCount)
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
     * @param $merchantId
     * @return array
     */
    public function base($merchantId)
    {
        $faker = Factory::create('nl-NL');
        $base = [
            'settlement_type' => Arr::random([1, 2]),
            'special_remark' => $faker->sentence(2, true),
            'remark' => $faker->sentence(2, true),
            'merchant_id' => $merchantId
        ];
        return $base;
    }
}
