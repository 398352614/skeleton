define({ "api": [
  {
    "type": "get",
    "url": "/merchant/last-week",
    "title": "上周订单总量",
    "group": "首页",
    "name": "上周订单总量",
    "version": "1.0.0",
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
            "type": "Object",
            "optional": false,
            "field": "data.date",
            "description": "<p>日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.order",
            "description": "<p>订单数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[{\"date\":\"2021-06-07\",\"order\":0},{\"date\":\"2021-06-08\",\"order\":0},{\"date\":\"2021-06-09\",\"order\":2}],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/last-week"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/last-month",
    "title": "上月订单总量",
    "group": "首页",
    "name": "上月订单总量",
    "version": "1.0.0",
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
            "type": "Object",
            "optional": false,
            "field": "data.date",
            "description": "<p>日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.order",
            "description": "<p>订单数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[{\"date\":\"2021-06-07\",\"order\":0},{\"date\":\"2021-06-08\",\"order\":0},{\"date\":\"2021-06-09\",\"order\":2}],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/last-month"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/this-week",
    "title": "今日订单情况",
    "group": "首页",
    "name": "今日订单情况",
    "version": "1.0.0",
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
            "type": "Object",
            "optional": false,
            "field": "data.date",
            "description": "<p>日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.order",
            "description": "<p>订单数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"doing\":0,\"done\":0,\"cancel\":0},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/this-week"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/period",
    "title": "时间段订单总量",
    "group": "首页",
    "name": "时间段订单总量",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "begin_date",
            "description": "<p>[必填]起始日期</p>"
          },
          {
            "group": "Parameter",
            "type": "date",
            "optional": false,
            "field": "end_date",
            "description": "<p>[必填]终止日期</p>"
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
            "type": "Object",
            "optional": false,
            "field": "data.date",
            "description": "<p>日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.order",
            "description": "<p>订单数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[{\"date\":\"2021-06-07\",\"order\":0},{\"date\":\"2021-06-08\",\"order\":0},{\"date\":\"2021-06-09\",\"order\":2}],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/period"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/this-week",
    "title": "本周订单总量",
    "group": "首页",
    "name": "本周订单总量",
    "version": "1.0.0",
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
            "type": "Object",
            "optional": false,
            "field": "data.date",
            "description": "<p>日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.order",
            "description": "<p>订单数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[{\"date\":\"2021-06-07\",\"order\":0},{\"date\":\"2021-06-08\",\"order\":0},{\"date\":\"2021-06-09\",\"order\":2}],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/this-week"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/this-month",
    "title": "本月订单总量",
    "group": "首页",
    "name": "本月订单总量",
    "version": "1.0.0",
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
            "type": "Object",
            "optional": false,
            "field": "data.date",
            "description": "<p>日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.order",
            "description": "<p>订单数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[{\"date\":\"2021-06-07\",\"order\":0},{\"date\":\"2021-06-08\",\"order\":0},{\"date\":\"2021-06-09\",\"order\":2}],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/this-month"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/this-week",
    "title": "订单动态",
    "group": "首页",
    "name": "订单动态",
    "version": "1.0.0",
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
            "type": "Object",
            "optional": false,
            "field": "data.date",
            "description": "<p>日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.order",
            "description": "<p>订单数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[{\"date\":\"2021-06-07\",\"order\":0},{\"date\":\"2021-06-08\",\"order\":0},{\"date\":\"2021-06-09\",\"order\":2}],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/this-week"
      }
    ]
  }
] });
