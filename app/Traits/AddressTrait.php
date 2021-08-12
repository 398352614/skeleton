<?php


namespace App\Traits;


Trait AddressTrait
{
    public static $address = [
        "fullname",
        "phone",
        "country",
        "post_code",
        "house_number",
        "city",
        "street",
        "province",
        "district",
        "lon",
        "lat",
        "address"
    ];

    public static $place = [
        "place_fullname",
        "place_phone",
        "place_country",
        "place_post_code",
        "place_house_number",
        "place_city",
        "place_street",
        "place_province",
        "place_district",
        "place_lon",
        "place_lat",
        "place_address"
    ];

    public static $secondPlace = [
        "second_place_fullname",
        "second_place_phone",
        "secound_place_country",
        "second_place_post_code",
        "second_place_house_number",
        "second_place_city",
        "second_place_street",
        "second_place_lon",
        "second_place_lat",
        "second_place_province",
        "second_place_district",
        "second_place_address"
    ];

    public static $warehouse = [
        "warehouse_fullname",
        "warehouse_phone",
        "warehouse_country",
        "warehouse_post_code",
        "warehouse_house_number",
        "warehouse_city",
        "warehouse_street",
        "warehouse_lon",
        "warehouse_lat",
        "warehouse_province",
        "warehouse_district",
        "warehouse_address",
    ];

    /**
     * 地址转第二地址
     * @param $from
     * @param array $to
     * @return mixed
     */
    public static function placeToSecondPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
        } else {
            $data = $to;
        }
        foreach (self::$place as $k => $v) {
            if (!empty($from[$v])) {
                $data['second_' . $v] = $from[$v];
            }elseif (empty($data[$v])) {
                $data[$v] = '';
            }
        }
        return $data;
    }

    /**
     * 第二地址转地址
     * @param $from
     * @param array $to
     * @return mixed
     */
    public static function secondPlaceToPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
        } else {
            $data = $to;
        }
        foreach (self::$place as $k => $v) {
            if (!empty($from['second_' . $v])) {
                $data[$v] = $from['second_' . $v];
            } elseif (empty($data[$v])) {
                $data[$v] = '';
            }
        }
        return $data;
    }

    /**
     * 仓库转地址
     * @param $from
     * @param array $to
     * @return mixed
     */
    public static function warehouseToPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
        } else {
            $data = $to;
        }
        foreach (self::$address as $k => $v) {
            if (!empty($from['warehouse_' . $v])) {
                $data['place_' . $v] = $from['warehouse_' . $v];
            }elseif (empty($data[$v])) {
                $data[$v] = '';
            }
        }
        return $data;
    }

    /**
     * 仓库转第二地址
     * @param $from
     * @param array $to
     * @return mixed
     */
    public static function warehouseToSecondPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
        } else {
            $data = $to;
        }
        foreach (self::$address as $k => $v) {
            if (!empty($from['warehouse_' . $v])) {
                $data['second_place_' . $v] = $from['warehouse_' . $v];
            }elseif (empty($data[$v])) {
                $data[$v] = '';
            }
        }
        return $data;
    }

    /**
     * 位置转地址（仅供getLocation）
     * @param $from
     * @param $to
     * @return mixed
     */
    public static function addressToPlace($from, $to)
    {
        $data = $to;
        foreach (self::$address as $k => $v) {
            if (!empty($from[$v])) {
                $data['place_' . $v] = $from[$v];
            }elseif (empty($data[$v])) {
                $data[$v] = '';
            }
        }
        $data['place_country'] = $to['place_country'];
        return $data;
    }

    /**
     * 地址转位置
     * @param $from
     * @param $to
     * @return mixed
     */
    public static function placeToAddress($from, $to)
    {
        $data = $to;
        foreach (self::$address as $k => $v) {
            if (!empty($from['place_' . $v])) {
                $data[$v] = $from['place_' . $v];
            }elseif (empty($data[$v])) {
                $data[$v] = '';
            }
        }
        return $data;
    }

    /**
     * 第二地址转位置
     * @param $from
     * @param $to
     * @return mixed
     */
    public static function secondPlaceToAddress($from, $to)
    {
        $data = $to;
        foreach (self::$address as $k => $v) {
            if (!empty($from['second_place_' . $v])) {
                $data[$v] = $from['second_place_' . $v];
            }elseif (empty($data[$v])) {
                $data[$v] = '';
            }
        }
        return $data;
    }

    /**
     * 仓库转位置
     * @param $from
     * @param $to
     * @return mixed
     */
    public static function warehouseToAddress($from, $to)
    {
        $data = $to;
        foreach (self::$address as $k => $v) {
            if (!empty($from['warehouse_' . $v])) {
                $data[$v] = $from['warehouse_' . $v];
            }elseif (empty($data[$v])) {
                $data[$v] = '';
            }
        }
        return $data;
    }

    /**
     * 互换地址和第二地址
     * @param $data
     * @return mixed
     */
    public static function changePlaceAndSecondPlace($data)
    {
        $params = [];
        foreach (self::$place as $k => $v) {
            $params[$v] = $data['second_' . $v] ?? '';
            $data['second_' . $v] = $data[$v] ?? '';
            $data[$v] = $params[$v] ?? '';
        }
        return $data;
    }

    public static function unsetPlace($data)
    {
        foreach (self::$place as $k => $v) {
            unset($data[$v]);
        }
        return $data;
    }

    public static function unsetSecondPlace(array $data)
    {
        foreach (self::$secondPlace as $k => $v) {
            unset($data[$v]);
        }
        return $data;
    }
}
