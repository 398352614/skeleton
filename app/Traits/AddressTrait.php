<?php


namespace App\Traits;


Trait AddressTrait
{
    public $address = [
        "fullname",
        "phone",
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

    public $place = [
        "place_fullname",
        "place_phone",
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

    public $secondPlace = [
        "second_place_fullname",
        "second_place_phone",
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

    public $warehouse = [
        "warehouse_fullname",
        "warehouse_phone",
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
    public function placeToSecondPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
            foreach ($this->place as $k => $v) {
                $data['second_' . $v] = $from[$v] ?? '';
            }
        } else {
            $data = $to;
            foreach ($this->place as $k => $v) {
                $data['second_' . $v] = $from[$v] ?? '';
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
    public function secondPlaceToPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
            foreach ($this->place as $k => $v) {
                $data[$v] = $from['second_' . $v] ?? '';
            }
        } else {
            $data = $to;
            foreach ($this->place as $k => $v) {
                $data[$v] = $from['second_' . $v] ?? '';
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
    public function warehouseToPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
            foreach ($this->address as $k => $v) {
                $data['place_' . $v] = $from['warehouse_' . $v] ?? '';
            }
        } else {
            $data = $to;
            foreach ($this->address as $k => $v) {
                $data['place_' . $v] = $from['warehouse_' . $v] ?? '';
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
    public function warehouseToSecondPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
            foreach ($this->address as $k => $v) {
                $data['second_place_' . $v] = $from['warehouse_' . $v] ?? '';
            }
        } else {
            $data = $to;
            foreach ($this->address as $k => $v) {
                $data['second_place_' . $v] = $from['warehouse_' . $v] ?? '';
            }
        }
        return $data;
    }

    /**
     * 仓库转仓库
     * @param $from
     * @param array $to
     * @return mixed
     */
    public function warehouseToWarehouse($from, $to)
    {
        $data = $to;
        foreach ($this->warehouse as $k => $v) {
            $data[$v] = $from[$v] ?? '';
        }
        return $data;
    }

    /**
     * 互换地址和第二地址
     * @param $data
     * @return mixed
     */
    public function changePlaceAndSecondPlace($data)
    {
        $params = [];
        foreach ($this->place as $k => $v) {
            $params[$v] = $data['second_' . $v] ?? '';
            $data['second_' . $v] = $data[$v] ?? '';
            $data[$v] = $params[$v] ?? '';
        }
        return $data;
    }

    public function unsetPlace($data)
    {
        foreach ($this->place as $k => $v) {
            unset($data[$v]);
        }
        return $data;
    }

    public function unsetSecondPlace(array $data)
    {
        foreach ($this->secondPlace as $k => $v) {
            unset($data[$v]);
        }
        return $data;
    }
}
