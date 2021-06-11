define({ "api": [
  {
    "type": "post",
    "url": "/api/merchant_api/post-code-date-list",
    "title": "获取可选日期",
    "name": "获取可选日期",
    "group": "01order",
    "permission": [
      {
        "name": "merchant"
      }
    ],
    "version": "1.0.0",
    "description": "<p>通过地址，获取可下单的日期</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "receiver_post_code",
            "description": "<p>邮编</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码，200：请求成功</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"msg\":\"\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\"code\":1000,\"msg\":\"提错提示\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "订单管理"
  },
  {
    "type": "post",
    "url": "/merchant/order",
    "title": "订单新增",
    "name": "订单新增",
    "group": "01order",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "order_no",
            "description": "<p>订单号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "execution_date",
            "description": "<p>取派日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_execution_date",
            "description": "<p>取派日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "create_date",
            "description": "<p>开单日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "out_order_no",
            "description": "<p>外部订单号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "mask_code",
            "description": "<p>掩码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "source",
            "description": "<p>来源</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "source_name",
            "description": "<p>来源名称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型:1-取2-派3-取派</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "out_user_id",
            "description": "<p>外部客户ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "nature",
            "description": "<p>性质:1-包裹2-材料3-文件4-增值服务5-其他</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "settlement_type",
            "description": "<p>结算类型1-寄付2-到付</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "settlement_amount",
            "description": "<p>结算金额</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "replace_amount",
            "description": "<p>代收货款</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_place_fullname",
            "description": "<p>收件人姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_place_phone",
            "description": "<p>收件人电话</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_place_country",
            "description": "<p>收件人国家</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_place_country_name",
            "description": "<p>收件人国家名称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_place_post_code",
            "description": "<p>收件人邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_place_house_number",
            "description": "<p>收件人门牌号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_place_city",
            "description": "<p>收件人城市</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_place_street",
            "description": "<p>收件人街道</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_place_address",
            "description": "<p>收件人详细地址</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_fullname",
            "description": "<p>发件人姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_phone",
            "description": "<p>发件人电话</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_country",
            "description": "<p>发件人国家</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_country_name",
            "description": "<p>发件人国家名称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_province",
            "description": "<p>发件人省份</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_post_code",
            "description": "<p>发件人邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_house_number",
            "description": "<p>发件人门牌号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_city",
            "description": "<p>发件人城市</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_district",
            "description": "<p>发件人区县</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_street",
            "description": "<p>发件人街道</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_address",
            "description": "<p>发件人详细地址</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "special_remark",
            "description": "<p>特殊事项</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "starting_price",
            "description": "<p>起步价</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "transport_price_type",
            "description": "<p>运价方案ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "receipt_type",
            "description": "<p>回单要求</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "receipt_type_name",
            "description": "<p>回单要求名称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "receipt_count",
            "description": "<p>回单数量</p>"
          },
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "package_list",
            "description": "<p>包裹列表</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.expiration_date",
            "description": "<p>有效日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.name",
            "description": "<p>包裹名称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.express_first_no",
            "description": "<p>快递单号1</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.express_second_no",
            "description": "<p>快递单号2</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.feature_logo",
            "description": "<p>特性标志</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.out_order_no",
            "description": "<p>外部标识</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.weight",
            "description": "<p>重量</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.size",
            "description": "<p>重量</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.actual_weight",
            "description": "<p>实际重量</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.expect_quantity",
            "description": "<p>预计数量</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.actual_quantity",
            "description": "<p>实际数量</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.sticker_no",
            "description": "<p>贴单号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.settlement_amount",
            "description": "<p>结算金额</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.count_settlement_amount",
            "description": "<p>估算运费</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.sticker_amount",
            "description": "<p>贴单费用</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.delivery_amount",
            "description": "<p>提货费用</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.is_auth",
            "description": "<p>是否需要身份验证1-是2-否</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.auth_fullname",
            "description": "<p>身份人姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "package_list.auth_birth_date",
            "description": "<p>身份人出身年月</p>"
          },
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "material_list",
            "description": "<p>材料列表</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.execution_date",
            "description": "<p>取派日期</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.name",
            "description": "<p>材料名称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.code",
            "description": "<p>材料代码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.out_order_no",
            "description": "<p>外部标识</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.expect_quantity",
            "description": "<p>预计数量</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.actual_quantity",
            "description": "<p>实际数量</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.pack_type",
            "description": "<p>包装类型</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.type",
            "description": "<p>类型</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.weight",
            "description": "<p>重量</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.size",
            "description": "<p>体积</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "material_list.remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "amount_list",
            "description": "<p>费用列表</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "amount_list.id",
            "description": "<p>费用ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "amount_list.expect_amount",
            "description": "<p>预计金额</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "amount_list.actual_amount",
            "description": "<p>实际金额</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "amount_list.type",
            "description": "<p>运费类型</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "amount_list.remark",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码，200：请求成功</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.id",
            "description": "<p>ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.order_no",
            "description": "<p>订单号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.out_order_no",
            "description": "<p>外部订单号</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"id\":4207,\"order_no\":\"SMAAAEM0001\",\"out_order_no\":\"DEVV21904566802\"},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order"
      }
    ],
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "language",
            "description": "<p>语言cn-中文en-英文。</p>"
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Authorization",
            "description": "<p>[必填]令牌，以bearer加空格加令牌为格式。</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n      \"language\": \"en\"\n      \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw\"\n    }",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/merchant/order/get-date",
    "title": "通过地址获取可选日期",
    "name": "通过地址获取可选日期",
    "group": "01order",
    "version": "1.0.0",
    "description": "<p>地址模板为一时，经纬度必填；地址模板为二时，邮编必填。</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>订单ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型1-取件2-派件</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_lon",
            "description": "<p>经度</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_lat",
            "description": "<p>纬度</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_post_code",
            "description": "<p>邮编</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "code",
            "description": "<p>状态码，200：请求成功</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[\"2021-06-11\",\"2021-06-13\",\"2021-06-16\",\"2021-06-18\",\"2021-06-20\"],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order/get-date"
      }
    ],
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "language",
            "description": "<p>语言cn-中文en-英文。</p>"
          },
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "Authorization",
            "description": "<p>[必填]令牌，以bearer加空格加令牌为格式。</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n      \"language\": \"en\"\n      \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw\"\n    }",
          "type": "json"
        }
      ]
    }
  }
] });
