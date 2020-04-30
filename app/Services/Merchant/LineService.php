<?php
/**
 * 线路 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:05
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\Line;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

class LineService extends BaseService
{
    public function __construct(Line $line)
    {
        parent::__construct($line);
    }

    /**
     * 线路范围 服务
     * @return LineRangeService
     */
    public function getLineRangeService()
    {
        return self::getInstance(LineRangeService::class);
    }

    /**
     * 取件线路 服务
     * @return TourService
     */
    private function getTourService()
    {
        return self::getInstance(TourService::class);
    }

    /**
     * 获取线路信息
     * @param $info
     * @param $orderOrBatch
     * @return array
     * @throws BusinessLogicException
     */
    public function getInfoByRule($info, $orderOrBatch = BaseConstService::ORDER_OR_BATCH_1)
    {
        $info['receiver_post_code'] = trim($info['receiver_post_code']);
        //获取邮编数字部分
        $postCode = explode_post_code($info['receiver_post_code']);
        //获取线路范围
        $lineRange = $this->getLineRangeService()->getInfo(['post_code_start' => ['<=', $postCode], 'post_code_end' => ['>=', $postCode], 'schedule' => Carbon::parse($info['execution_date'])->dayOfWeek, 'country' => $info['receiver_country']], ['*'], false);
        if (empty($lineRange)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        $lineRange = $lineRange->toArray();
        //获取线路信息
        $line = parent::getInfo(['id' => $lineRange['line_id']], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        $line = $line->toArray();
        //预约当天的，需要判断是否在下单截止日期内
        if (date('Y-m-d') == $info['execution_date']) {
            if (time() > strtotime($info['execution_date'] . ' ' . $line['order_deadline'])) {
                throw new BusinessLogicException('当天下单已超过截止时间');
            }
        }
        //判断预约日期是否在可预约日期范围内
        if (Carbon::today()->addDays($line['appointment_days'])->lt($info['execution_date'] . ' 00:00:00')) {
            throw new BusinessLogicException('预约日期已超过可预约时间范围');
        }
        //若不是新增取件线路，则当前取件线路必须再最大订单量内
        if ($line['is_increment'] == BaseConstService::IS_INCREMENT_2) {
            if ($orderOrBatch === BaseConstService::ORDER_OR_BATCH_1) {
                if ($info['type'] == 1) {
                    $orderCount = $this->getTourService()->sumOrderCount($info, $line, 1);
                    if (1 + $orderCount['pickup_count'] > $line['pickup_max_count']) {
                        throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                    };
                } else {
                    $orderCount = $this->getTourService()->sumOrderCount($info, $line, 2);
                    if (1 + $orderCount['pie_count'] > $line['pie_max_count']) {
                        throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                    };
                }
            } else {
                $orderCount = $this->getTourService()->sumOrderCount($info, $line, 3);
                if ($info['expect_pickup_quantity'] + $orderCount['pickup_count'] > $line['pickup_max_count']) {
                    throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                };
                if ($info['expect_pie_quantity'] + $orderCount['pie_count'] > $line['pie_max_count']) {
                    throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                };
            }
        }
        return $line;
    }
}
