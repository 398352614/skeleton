define({ "api": [
  {
    "type": "post",
    "url": "/merchant_api",
    "title": "全局说明",
    "name": "全局说明",
    "group": "00api",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "language",
            "description": "<p>语言cn-中文en-英文。</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n      \"language\": \"en\"\n    }",
          "type": "json"
        }
      ]
    },
    "description": "<p>加密方式示例文件，暂时仅提供php版本，如有需要请联系技术人员。</p>",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "key",
            "description": "<p>[必填]  秘钥：从管理员端新增货主时，会自动生成一个key，在资料管理-API对接管理中，可查询对应key，用以确认货主身份。</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "sign",
            "description": "<p>[必填]  签名：sgin是以secret和data以一定加密方式形成的签名，每次请求都会验证key和sign以验证数据可靠。key或sign任一项不正确，请求都将被拒绝。从管理员端新增货主时，会自动生成一个secret，在资料管理-API对接管理中，可查询对应secret。sign的生成规则为：1，平铺data内的数组，生成一个字符串；2，将1的结果与secret连接起来；3，对2的结果其进行url编码；4，将3的结果全部转化为大写。</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "timestamp",
            "description": "<p>[必填]  时间戳：发送请求时的时间戳。</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>[必填]  主体数据。以下所有接口的参数都是以json的形式放在这个参数里。</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Param-Response:",
          "content": "{\"key\":\"请在此处填上货主的key\",\"sign\":\"以货主的secret和主体数据生成的动态签名\",\"timestamp\":1623986460,\"data\":{\"order_no\":\"TMS1000000001\",\"...\":\"...\"}}",
          "type": "json"
        }
      ]
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
      }
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "3001",
            "description": "<p>数据验证未通过</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "1000",
            "description": "<p>业务逻辑抛错</p>"
          }
        ]
      }
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "全局说明",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant_api"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant_api/order-out-status",
    "title": "允许出库",
    "name": "允许出库",
    "group": "01order",
    "version": "1.0.0",
    "description": "<p>当订单的控货方式选择为等通知放货时，订单默认无法出库，只有等货主请求该接口，才能让这些订单变为可出库状态。</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "order_no",
            "description": "<p>[必填] 订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "out_status",
            "description": "<p>是否允许出库，1-允许2-不允许。</p>"
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
            "field": "data.data1",
            "description": "<p>返回数据</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "接口使用方法",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant_api/order-out-status"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant_api/cancel-all-order",
    "title": "取消预约",
    "name": "删除订单",
    "group": "01order",
    "version": "1.0.0",
    "description": "<p>订单状态分为1-待受理2-取派中3-已完成4-取派失败5-回收站，取消预约功能只有订单在待受理状态才能使用。取消预约后，无法通过货主端恢复。</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "order_no_list",
            "description": "<p>一个或多个订单号，以逗号连接</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "no_push",
            "description": "<p>是否推送1-是，货主通过该API删除订单，不会通知货主该订单已删除。2-否，货主通过该API删除订单，仍会通知货主订单已删除。</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"order_no_list\":\"TMS00001,TMS00002,TMS00003\",\"no_push\":1}",
          "type": "json"
        }
      ]
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
          "content": "{\"code\":200,\"data\":[],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "接口使用方法",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant_api/cancel-all-order"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant_api/order-dispatch-info",
    "title": "物流查询",
    "name": "物流查询",
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
            "description": "<p>[必填] 订单编号</p>"
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
            "field": "data.expect_distance",
            "description": "<p>预计里程（公里）</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.actual_distance",
            "description": "<p>实际里程（公里）</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.expect_time",
            "description": "<p>预计耗时(秒)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.actual_time",
            "description": "<p>实际耗时(秒)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.expect_arrive_time",
            "description": "<p>预计到达时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.actual_arrive_time",
            "description": "<p>实际到达时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_lon",
            "description": "<p>客户经度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_lat",
            "description": "<p>客户纬度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.driver_lon",
            "description": "<p>司机经度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.driver_lat",
            "description": "<p>司机纬度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.out_order_no",
            "description": "<p>外部订单号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.rest_batch",
            "description": "<p>剩余站点数，指运到该客户之前，还有多少个客户没运。</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"expect_distance\":0.04,\"actual_distance\":null,\"expect_time\":null,\"actual_time\":12,\"expect_arrive_time\":\"2020-05-12 16:43:22\",\"actual_arrive_time\":\"2020-05-12 16:42:43\",\"place_lon\":\"4.87510019\",\"place_lat\":\"52.311530833\",\"driver_lon\":\"4.87510019\",\"driver_lat\":\"52.31153083\",\"out_order_no\":\"12\",\"rest_batch\":1},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "接口使用方法",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant_api/order-dispatch-info"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant_api/end-order",
    "title": "终止派送",
    "name": "终止派送",
    "group": "01order",
    "version": "1.0.0",
    "description": "<p>只有取件和派件过程中，取消的订单才能进行终止派送。终止派送后，订单会变成取派失败状态，不能再进行继续派送。</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "order_no",
            "description": "<p>[必填] 订单编号</p>"
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
          "content": "{\"code\":200,\"data\":[],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "接口使用方法",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant_api/end-order"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant_api/again-order",
    "title": "继续派送",
    "name": "继续派送",
    "group": "01order",
    "version": "1.0.0",
    "description": "<p>只有取件和派件过程中，取消的订单才能进行继续派送。继续派送会重新进行取派，该接口提供客户地址，客户电话，客户姓名的修改，填写新的地址信息后，会按新的地址信息进行取派。</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "order_no",
            "description": "<p>[必填] 订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_fullname",
            "description": "<p>客户姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_phone",
            "description": "<p>客户电话</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_province",
            "description": "<p>客户省份</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_post_code",
            "description": "<p>客户邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_house_number",
            "description": "<p>客户门牌号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_city",
            "description": "<p>客户城市</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_district",
            "description": "<p>客户地区</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_street",
            "description": "<p>客户街道</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_lon",
            "description": "<p>客户经度</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_lat",
            "description": "<p>客户纬度</p>"
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
          "content": "{\"code\":200,\"data\":[],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "接口使用方法",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant_api/again-order"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant_api/location",
    "title": "查询地理信息",
    "name": "获取地址",
    "group": "01order",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_country",
            "description": "<p>[必填] 国家</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_province",
            "description": "<p>省份</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_city",
            "description": "<p>城市</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_district",
            "description": "<p>区县</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_post_code",
            "description": "<p>邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_street",
            "description": "<p>街道</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_house_number",
            "description": "<p>门牌号</p>"
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
            "field": "data.place_country",
            "description": "<p>国家</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_province",
            "description": "<p>省份</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_city",
            "description": "<p>城市</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_district",
            "description": "<p>区县</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_street",
            "description": "<p>街道</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_house_number",
            "description": "<p>门牌号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_lon",
            "description": "<p>经度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_lat",
            "description": "<p>纬度</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"place_country\":\"NL\",\"place_province\":\"\",\"place_post_code\":\"1086ZK\",\"place_house_number\":\"46\",\"place_city\":\"Amsterdam\",\"place_district\":\"\",\"place_street\":\"Cornelis Zillesenlaan\",\"place_lon\":\"4.98113818\",\"place_lat\":\"52.36200569\"},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "接口使用方法",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant_api/location"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant_api/order-update",
    "title": "订单修改",
    "name": "订单修改",
    "group": "01order",
    "version": "1.0.0",
    "description": "<p>只有待受理的订单才能进行修改。</p>",
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
            "description": "<p>[必填] 取派日期</p>"
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
            "description": "<p>[必填] 发件人姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_phone",
            "description": "<p>[必填] 发件人电话</p>"
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
            "field": "place_province",
            "description": "<p>发件人省份</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_post_code",
            "description": "<p>[必填] 发件人邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_house_number",
            "description": "<p>[必填] 发件人门牌号</p>"
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
            "description": "<p>[必填] 快递单号1</p>"
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
            "description": "<p>[必填] 预计数量</p>"
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
            "description": "<p>[必填] 材料代码</p>"
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
          "content": "{\"code\":200,\"data\":[],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/01order.php",
    "groupTitle": "接口使用方法",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant_api/order-update"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant_api/order",
    "title": "订单新增",
    "name": "订单新增",
    "group": "01order",
    "version": "1.0.0",
    "description": "<p>订单新增有两种模式，当类型为取件或者派件时，只需要填写取派日期execution_date和地址(以'place_'为前缀的字段)，当类型为取派件时，第二取派日期second_execution_date和第二地址(以'second_place_'为前缀的字段)也需要填写，第二用户地址为派件地址，另一个为取件。</p>",
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
            "description": "<p>[必填]取派日期。</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "second_execution_date",
            "description": "<p>取派日期。若订单类型为取派件，则此项必填。</p>"
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
            "description": "<p>[必填] 类型:1-取2-派3-取派</p>"
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
            "description": "<p>[必填] 发件人姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_phone",
            "description": "<p>[必填]  发件人电话</p>"
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
            "field": "place_province",
            "description": "<p>发件人省份</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_post_code",
            "description": "<p>[必填] 发件人邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_house_number",
            "description": "<p>[必填] 发件人门牌号</p>"
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
            "description": "<p>[必填] 快递单号</p>"
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
            "description": "<p>[必填] 预计数量</p>"
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
            "description": "<p>[必填] 材料代码</p>"
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
            "description": "<p>[必填] 预计数量</p>"
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
    "groupTitle": "接口使用方法",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant_api/order"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant_api/post-code-date-list",
    "title": "获取可预约日期",
    "name": "通过地址获取可预约日期",
    "group": "01order",
    "version": "1.0.0",
    "description": "<p>通过地址获取可预约日期，线路分配规则为邮编的情况，邮编必填；线路分配规则为区域的情况，经纬度必填，线路分配规则请在管理员端-配置管理-调度管理-调度规则页面确认或修改。</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "order_no",
            "description": "<p>[必填] 订单编号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>[必填] 类型1-取件2-派件</p>"
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
    "groupTitle": "接口使用方法",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant_api/post-code-date-list"
      }
    ]
  },
  {
    "type": "post",
    "url": "/assign-batch",
    "title": "状态转变",
    "name": "签收",
    "group": "02order",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "string",
            "optional": false,
            "field": "language",
            "description": "<p>语言cn-中文en-英文。</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n      \"language\": \"en\"\n    }",
          "type": "json"
        }
      ]
    },
    "description": "<p>地址栏仅为请求类型，不是真实的推送地址。推送地址由第三方提供，所有的推送都会推送到第三方提供的地址。目前仅提供简略模式，只推送状态不推送相关数据。对于请求返回，仅验证返回值中ret是否为1，1表示推送成功。如果返回值中没有ret或者不需要第三方推送记录，那么TMS将不会解析推送后的返回值。</p>",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型:签收assign-batch，出库out-warehouse</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.order_no",
            "description": "<p>订单号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.out_order_no",
            "description": "<p>外部订单号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.order_status",
            "description": "<p>订单状态1-待受理2-运输中3-已完成4-已失败5回收站</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.package_list",
            "description": "<p>包裹列表</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.package_list.express_first_no",
            "description": "<p>包裹号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.package_list.out_order_no",
            "description": "<p>外部包裹号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.package_list.stage",
            "description": "<p>包裹阶段1-取件2-派件3-中转</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.package_list.status",
            "description": "<p>包裹状态是包裹阶段的细分过程，其中只有取件和派件阶段拥有包裹状态1-未取派2-取派中3-已签收4-取派失败5-回收站。中转阶段的包裹仅显示为最后更新的取件或派件阶段的状态。</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Param-Response:",
          "content": "{\"type\":\"assign-batch\",\"data\":[{\"order_no\":\"TMS0001\",\"out_order_no\":\"ERP0001\",\"order_status\":4,\"package_list\":[{\"express_first_no\":\"TMSPA001\",\"out_order_no\":\"ERPPA001\",\"package_status\":4,\"package_stage\":1}]}]}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "ret",
            "description": "<p>状态码，1:成功</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"ret\":1}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchantApi/02push.php",
    "groupTitle": "推送通知",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/assign-batch"
      }
    ]
  }
] });
