define({ "api": [
  {
    "type": "put",
    "url": "/merchant/timezone",
    "title": "切换时区",
    "group": "公共接口",
    "name": "切换时区",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "timezone",
            "description": "<p>[必填]时区</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Param-Response:",
          "content": "{\"timezone\":\"GMT+00:00\"}",
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "公共接口",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/timezone"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant/login",
    "title": "登录",
    "group": "用户认证",
    "name": "登录",
    "version": "1.0.0",
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
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "username",
            "description": "<p>[必填]用户名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>[必填]密码</p>"
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
            "field": "data.username",
            "description": "<p>用户名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.access_token",
            "description": "<p>认证令牌</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.token_type",
            "description": "<p>令牌类型</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.expires_in",
            "description": "<p>令牌有效时间</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.company_config",
            "description": "<p>公司配置</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.id",
            "description": "<p>公司配置ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.company_code",
            "description": "<p>公司编号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.name",
            "description": "<p>公司名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.line_rule",
            "description": "<p>线路规则1-邮编2-区域</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.show_type",
            "description": "<p>展示方式1-全部展示；2-按线路规则展示</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.address_template_id",
            "description": "<p>地址模板ID1-模板一2-模板二</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.stock_exception_verify",
            "description": "<p>是否开启入库异常审核1-开启2-关闭</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.weight_unit",
            "description": "<p>重量单位</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.currency_unit",
            "description": "<p>货币单位</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.volume_unit",
            "description": "<p>体积单位</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.map",
            "description": "<p>地图引擎</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.country",
            "description": "<p>国家代号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.country_en_name",
            "description": "<p>国家英文名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.country_cn_name",
            "description": "<p>国家中文名</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"username\":\"ERP\\u56fd\\u9645\",\"company_id\":3,\"access_token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvbWVyY2hhbnRcL2xvZ2luIiwiaWF0IjoxNjIzMjI4ODY1LCJleHAiOjE2MjgwNjcyNjUsIm5iZiI6MTYyMzIyODg2NSwianRpIjoiTEJOaXpOTGhIVTZrd2ttZCIsInN1YiI6NjUsInBydiI6IjkzYmRjYzU4ZGQwMWNlMzZlYzU2ZTMyYjViYjU4MGQ4MzAzMmZkMTgiLCJyb2xlIjoibWVyY2hhbnQifQ.LpBWSItYcjeFuSwEf_FIqa2qO7BXe57biqSrsELk6n4\",\"token_type\":\"bearer\",\"expires_in\":4838400,\"company_config\":{\"id\":3,\"company_code\":\"0003\",\"name\":\"\\u7ea2\\u5154TMS\",\"line_rule\":1,\"show_type\":1,\"address_template_id\":1,\"stock_exception_verify\":2,\"weight_unit\":2,\"currency_unit\":3,\"volume_unit\":2,\"map\":\"google\",\"country\":\"NL\",\"country_en_name\":\"Netherlands\",\"country_cn_name\":\"\\u8377\\u5170\"}},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "用户认证",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/login"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant/password-reset/apply",
    "title": "获取重置密码验证码",
    "group": "用户认证",
    "name": "获取重置密码验证码",
    "version": "1.0.0",
    "header": {
      "fields": {
        "Header": [
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
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>[必填]邮箱</p>"
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
          "content": "{\"code\":200,\"data\":\"\\u9a8c\\u8bc1\\u7801\\u53d1\\u9001\\u6210\\u529f\",\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "用户认证",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/password-reset/apply"
      }
    ]
  },
  {
    "type": "post",
    "url": "/merchant/password-reset",
    "title": "重置密码",
    "group": "用户认证",
    "name": "重置密码",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>[必填]邮箱</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>[必填]验证码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "new_password",
            "description": "<p>[必填]新密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "confirm_new_password",
            "description": "<p>[必填]重复新密码</p>"
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "用户认证",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/password-reset"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/api",
    "title": "获取API对接信息",
    "group": "设置",
    "name": "API对接",
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
            "field": "data.id",
            "description": "<p>ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_id",
            "description": "<p>公司ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.key",
            "description": "<p>key</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.secret",
            "description": "<p>secret</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.url",
            "description": "<p>url</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.white_ip_list",
            "description": "<p>ip白名单</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.status",
            "description": "<p>状态1-是2-否</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.push_mode",
            "description": "<p>推送方式1-老模式2-详情模式3-简略模式4-自定义模式</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.recharge_status",
            "description": "<p>充值通道1-开启2关闭</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.created_at",
            "description": "<p>创建时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.updated_at",
            "description": "<p>更新时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.status_name",
            "description": "<p>状态名</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"id\":65,\"company_id\":3,\"merchant_id\":65,\"key\":\"5WKyJBO7jAKrQYBaV0Nz\",\"secret\":\"oepK9gmbBMxLZMj41e6DlzdnO0WD16Rr\",\"url\":\"https:\\/\\/dev-nl-erp-api.nle-tech.com\\/app\\/tms_push\",\"white_ip_list\":\"\",\"status\":1,\"push_mode\":2,\"recharge_status\":1,\"created_at\":\"2020-07-14 16:45:37\",\"updated_at\":\"2021-05-25 11:33:57\",\"status_name\":\"\\u662f\"},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "设置",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/api"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/me",
    "title": "个人资料",
    "group": "设置",
    "name": "个人资料",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "origin_password",
            "description": "<p>[必填]原密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "new_password",
            "description": "<p>[必填]新密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "new_confirm_password",
            "description": "<p>[必填]重复新密码</p>"
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
            "description": "<p>货主ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_id",
            "description": "<p>货主公司ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.name",
            "description": "<p>货主名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.email",
            "description": "<p>货主邮箱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.country",
            "description": "<p>货主国家</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.merchant_group_id",
            "description": "<p>商户组ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.contacter",
            "description": "<p>货主联系人</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.phone",
            "description": "<p>货主电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.address",
            "description": "<p>货主地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.avatar",
            "description": "<p>货主头像</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.status",
            "description": "<p>货主状态1-启用2-禁用</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.timezone",
            "description": "<p>时区</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.settlement_type",
            "description": "<p>结算方式1-票结2-日结3-月结</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.settlement_type_name",
            "description": "<p>结算方式1-票结2-日结3-月结</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"id\":65,\"company_id\":3,\"code\":\"00065\",\"type\":2,\"name\":\"ERP\\u56fd\\u9645\",\"below_warehouse\":2,\"warehouse_id\":null,\"short_name\":\"0\",\"introduction\":\"Nederlands Express\\uff0cNLE\\u8377\\u5170\\u5feb\\u9012\\uff08\\u4ee5\\u4e0b\\u7b80\\u79f0NLE\\uff09\\u603b\\u90e8\\u4f4d\\u4e8e\\u8377\\u5170\\uff0c\\u662f\\u8377\\u5170\\u6700\\u65e9\\u4e14\\u6700\\u5927\\u4e00\\u5bb6\\u4ece\\u4e8b\\u56fd\\u9645\\u7269\\u6d41\\u901f\\u9012\\u3001\\u4ed3\\u50a8\\u8fd0\\u8425\\u3001\\u7a7a\\u8fd0\\u3001\\u8d27\\u4ee3\\u7b49\\u7269\\u6d41\\u914d\\u9001\\u89e3\\u51b3\\u65b9\\u6848\\u7684\\u4e13\\u4e1a\\u56fd\\u9645\\u7269\\u6d41\\u516c\\u53f8\\u3002\",\"email\":\"erp@nle-tech.com\",\"country\":\"NL\",\"settlement_type\":1,\"merchant_group_id\":53,\"contacter\":\"\\u8054\\u7cfb\\u4eba1\",\"phone\":\"1312121211\",\"address\":\"\\u8be6\\u7ec6\\u5730\\u57401\",\"avatar\":\"\\u5934\\u50cf\",\"invoice_title\":\"1\",\"taxpayer_code\":\"0000-00-00\",\"bank\":\"0000-00-00\",\"bank_account\":\"0000-00-00\",\"invoice_address\":\"0000-00-00\",\"invoice_email\":\"0000-00-00\",\"status\":1,\"created_at\":\"2020-07-14 16:45:36\",\"updated_at\":\"2021-06-09 12:54:46\",\"company_config\":{\"id\":3,\"company_id\":3,\"line_rule\":1,\"show_type\":1,\"address_template_id\":1,\"stock_exception_verify\":2,\"weight_unit\":2,\"currency_unit\":3,\"volume_unit\":2,\"map\":\"google\",\"created_at\":\"2020-03-13 12:00:09\",\"updated_at\":\"2021-06-08 06:14:09\",\"scheduling_rule\":1},\"settlement_type_name\":\"\\u7968\\u7ed3\",\"status_name\":\"\\u542f\\u7528\",\"type_name\":\"\\u8d27\\u4e3b\",\"country_name\":\"\\u8377\\u5170\",\"additional_status\":1,\"advance_days\":0,\"appointment_days\":10,\"delay_time\":0,\"pickup_count\":1,\"pie_count\":2,\"merchant_group\":{\"id\":53,\"company_id\":3,\"name\":\"ERP\\u56fd\\u9645\\u7ec4\",\"transport_price_id\":67,\"count\":3,\"is_default\":2,\"additional_status\":1,\"advance_days\":0,\"appointment_days\":10,\"delay_time\":0,\"pickup_count\":1,\"pie_count\":2,\"created_at\":\"2020-12-28 03:26:41\",\"updated_at\":\"2021-03-18 09:00:48\",\"additional_status_name\":\"\\u5f00\\u542f\"}},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "设置",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/me"
      }
    ]
  },
  {
    "type": "put",
    "url": "/merchant/api",
    "title": "修改API对接信息",
    "group": "设置",
    "name": "修改API对接信息",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>[必填]url</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "white_ip_list",
            "description": "<p>ip白名单</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>[必填]状态1-是2-否</p>"
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "设置",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/api"
      }
    ]
  },
  {
    "type": "put",
    "url": "/merchant/password-reset",
    "title": "修改个人资料",
    "group": "设置",
    "name": "修改个人资料",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>[必填]商户名称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "contacter",
            "description": "<p>[必填]商户联系人</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>[必填]商户电话</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "address",
            "description": "<p>[必填]商户地址</p>"
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "设置",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/password-reset"
      }
    ]
  },
  {
    "type": "put",
    "url": "/merchant/my-password",
    "title": "修改密码",
    "group": "设置",
    "name": "修改密码",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "origin_password",
            "description": "<p>[必填]原密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "new_password",
            "description": "<p>[必填]新密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "new_confirm_password",
            "description": "<p>[必填]重复新密码</p>"
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "设置",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/my-password"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/statistics/last-week",
    "title": "上周订单总量",
    "group": "首页",
    "name": "上周订单总量",
    "version": "1.0.0",
    "header": {
      "fields": {
        "Header": [
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/last-week"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/statistics/last-month",
    "title": "上月订单总量",
    "group": "首页",
    "name": "上月订单总量",
    "version": "1.0.0",
    "header": {
      "fields": {
        "Header": [
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/last-month"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/statistics/this-week",
    "title": "今日订单情况",
    "group": "首页",
    "name": "今日订单情况",
    "version": "1.0.0",
    "header": {
      "fields": {
        "Header": [
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
          "content": "{\"code\":200,\"data\":{\"doing\":0,\"done\":0,\"cancel\":0},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/this-week"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/statistics/period",
    "title": "时间段订单总量",
    "group": "首页",
    "name": "时间段订单总量",
    "version": "1.0.0",
    "header": {
      "fields": {
        "Header": [
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
    },
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
      },
      "examples": [
        {
          "title": "Param-Response:",
          "content": "{\"begin_date\":\"2021-06-09\",\"end_date\":\"2021-06-09\"}",
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/period"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/statistics/this-week",
    "title": "本周订单总量",
    "group": "首页",
    "name": "本周订单总量",
    "version": "1.0.0",
    "header": {
      "fields": {
        "Header": [
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/this-week"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/statistics/this-month",
    "title": "本月订单总量",
    "group": "首页",
    "name": "本月订单总量",
    "version": "1.0.0",
    "header": {
      "fields": {
        "Header": [
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/this-month"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant/statistics/this-week",
    "title": "订单动态",
    "group": "首页",
    "name": "订单动态",
    "version": "1.0.0",
    "header": {
      "fields": {
        "Header": [
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
          "content": "{\n      \"language\": \"Accept-Encoding: gzip, deflate\"\n      \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw\"\n    }",
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
    "filename": "public/api/routes/merchant/api_doc_merchant.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/this-week"
      }
    ]
  }
] });
