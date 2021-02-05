<?php
/**
 * 运价 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Merchant;

use App\Http\Validate\BaseValidate;

class TransportPriceValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'name' => 'required|string|max:50|uniqueIgnore:transport_price,id',
        'starting_price' => 'required|numeric|gte:0',
        'remark' => 'nullable|string|max:250',
        'status' => 'required|integer|in:1,2',
        //公里计费列表
        'km_list.*.start' => 'required_with:km_list|integer',
        'km_list.*.end' => 'required_with:km_list|integer|gt:km_list.*.start',
        'km_list.*.price' => 'required_with:km_list|numeric|gte:0',
        //重量计费列表
        'weight_list.*.start' => 'required_with:weight_list|integer',
        'weight_list.*.end' => 'required_with:weight_list|integer|gt:weight_list.*.start',
        'weight_list.*.price' => 'required_with:weight_list|numeric|gte:0',
        //特殊时段计费列表
        'special_time_list.*.start' => 'required_with:special_time_list|date_format:H:i:s',
        'special_time_list.*.end' => 'required_with:special_time_list|date_format:H:i:s|after:special_time_list.*.start',
        'special_time_list.*.price' => 'required_with:special_time_list|numeric|gte:0',
        //运价计算
        'km' => 'nullable|integer|gte:0',
        'weight' => 'nullable|integer|gte:0',
        'special_time' => 'nullable|date_format:H:i:s',
        'distance'=>'required|integer|gte:0',
        'type'=>'required|integer|in:1,2',
        'package_list.*.weight'=>'required_with:package_list|string|gte:0',
    ];

    public $scene = [
        'store' => [
            'name', 'starting_price', 'remark', 'status',
            'km_list', 'km_list.*.start', 'km_list.*.end', 'km_list.*.price',
            'weight_list', 'weight_list.*.start', 'weight_list.*.end', 'weight_list.*.price',
            'special_time_list', 'special_time_list.*.start', 'special_time_list.*.end', 'special_time_list.*.price','type'
        ],
        'update' => [
            'name', 'starting_price', 'remark', 'status',
            'km_list', 'km_list.*.start', 'km_list.*.end', 'km_list.*.price',
            'weight_list', 'weight_list.*.start', 'weight_list.*.end', 'weight_list.*.price',
            'special_time_list', 'special_time_list.*.start', 'special_time_list.*.end', 'special_time_list.*.price','type'
        ],
        'getPriceResult' => [
            'km', 'weight', 'special_time'
        ],
        'priceCount'=>[
            'distance','package_list','package_list.*.weight'
        ]
    ];
}

