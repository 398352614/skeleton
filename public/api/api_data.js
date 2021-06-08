define({ "api": [
  {
    "type": "post",
    "url": "/api/merchant_api/post-code-date-list",
    "title": "获取可选日期",
    "group": "订单新增",
    "name": "获取可选日期",
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
    "filename": "routes/api_merchant_api.php",
    "groupTitle": "订单新增"
  },
  {
    "type": "post",
    "url": "/api/merchant_api/order",
    "title": "新增订单",
    "group": "订单管理",
    "name": "新增订单",
    "permission": [
      {
        "name": "merchant"
      }
    ],
    "version": "1.0.0",
    "description": "<p>通过表单信息新增一个订单</p>",
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
            "description": "<p>[必填]  签名：签名是以secret和data以一定加密方式形成的签名，每次请求都会验证key和sign以验证数据可靠。key或sign任一项不正确，请求都将被拒绝。 从管理员端新增货主时，会自动生成一个secret，在资料管理-API对接管理中，可查询对应secret。 sign的生成规则为：1，平铺data内的数组，生成一个字符串；2，将1的结果与secret连接起来；3，对2的结果其进行url编码；4，将3的结果全部转化为大写。</p>"
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
            "description": "<p>[必填]  数据</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.type",
            "description": "<p>[必填]  订单类型：1-提货-&gt;网点；2-网点-&gt;配送；3-&gt;提货-&gt;网点-&gt;配送；4-提货-&gt;配送。</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data.merchant_id",
            "description": "<p>[必填]  货主ID</p>"
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
            "field": "ret",
            "description": "<p>状态码，1：请求成功</p>"
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
          "content": "{\"ret\":1,\"msg\":\"\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\"ret\":0,\"msg\":\"提错提示\"}",
          "type": "json"
        }
      ]
    },
    "filename": "routes/api_merchant_api.php",
    "groupTitle": "订单管理"
  },
  {
    "type": "post",
    "url": "/api/merchant_api/me",
    "title": "账户信息",
    "group": "账号管理",
    "name": "账户信息",
    "permission": [
      {
        "name": "merchant"
      }
    ],
    "version": "1.0.0",
    "description": "<p>获取本货主的基本信息</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "$param1",
            "description": "<p>参数说明</p>"
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
            "field": "ret",
            "description": "<p>状态码，1：请求成功</p>"
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
          "content": "{\"ret\":1,\"msg\":\"\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\"ret\":0,\"msg\":\"提错提示\"}",
          "type": "json"
        }
      ]
    },
    "filename": "routes/api_merchant_api.php",
    "groupTitle": "账号管理"
  },
  {
    "type": "post",
    "url": "/api/merchant_api/me",
    "title": "账户信息",
    "group": "账号管理",
    "name": "账户信息",
    "permission": [
      {
        "name": "admin"
      }
    ],
    "version": "1.0.0",
    "description": "<p>获取本货主的基本信息</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "$param1",
            "description": "<p>参数说明</p>"
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
            "field": "ret",
            "description": "<p>状态码，1：请求成功</p>"
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
          "content": "{\"ret\":1,\"msg\":\"\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "{\"ret\":0,\"msg\":\"提错提示\"}",
          "type": "json"
        }
      ]
    },
    "filename": "routes/api_merchant_api.php",
    "groupTitle": "账号管理"
  }
] });
