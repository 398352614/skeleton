define({ "api": [
  {
    "type": "post",
    "url": "/merchant/login",
    "title": "登录",
    "name": "登录",
    "group": "01auth",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "username",
            "description": "<p>用户名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>密码</p>"
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
            "field": "data.company_id",
            "description": "<p>公司ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.access_token",
            "description": "<p>令牌</p>"
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
            "description": "<p>令牌过期时间（秒）</p>"
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
            "description": "<p>展示方式1-全部展示2-按线路规则展示</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.address_template_id",
            "description": "<p>地址模板ID</p>"
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
            "description": "<p>公司国家</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.country_en_name",
            "description": "<p>公司国家英文名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.country_cn_name",
            "description": "<p>公司国家中文名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.weight_unit_symbol",
            "description": "<p>重量单位标志</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.currency_unit_symbol",
            "description": "<p>货币单位标志</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_config.volume_unit_symbol",
            "description": "<p>体积单位标志</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"username\":\"TEST-TMS\",\"company_id\":3,\"access_token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC90bXMtYXBpLnRlc3Q6MTAwMDJcL2FwaVwvbWVyY2hhbnRcL2xvZ2luIiwiaWF0IjoxNjIzMzA3NTQ3LCJleHAiOjE2MjQ1MTcxNDcsIm5iZiI6MTYyMzMwNzU0NywianRpIjoiRElndDJiV3I1QXpjbXpZZSIsInN1YiI6MTI1LCJwcnYiOiI5M2JkY2M1OGRkMDFjZTM2ZWM1NmUzMmI1YmI1ODBkODMwMzJmZDE4Iiwicm9sZSI6Im1lcmNoYW50In0.dzg39jx5CtudehicegkNXMPKjaVyK_-db1VXmyaEavI\",\"token_type\":\"bearer\",\"expires_in\":1209600,\"company_config\":{\"id\":3,\"company_code\":\"0003\",\"name\":\"\\u7ea2\\u5154TMS\",\"company_id\":3,\"line_rule\":1,\"show_type\":1,\"address_template_id\":1,\"stock_exception_verify\":2,\"weight_unit\":2,\"currency_unit\":3,\"volume_unit\":2,\"map\":\"google\",\"created_at\":\"2020-03-13 12:00:09\",\"updated_at\":\"2021-06-08 06:14:09\",\"scheduling_rule\":1,\"weight_unit_symbol\":\"lb\",\"currency_unit_symbol\":\"\\u20ac\",\"volume_unit_symbol\":\"m\\u00b3\",\"country\":\"NL\",\"country_en_name\":\"Netherlands\",\"country_cn_name\":\"\\u8377\\u5170\"}},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/01auth.php",
    "groupTitle": "用户认证",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/login"
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
    "type": "put",
    "url": "/merchant/password/reset",
    "title": "重置密码",
    "name": "重置密码",
    "group": "01auth",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>验证码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "new_password",
            "description": "<p>新密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "confirm_new_password",
            "description": "<p>重复新密码</p>"
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
    "filename": "public/api/routes/merchant/01auth.php",
    "groupTitle": "用户认证",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/password/reset"
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
    "type": "post",
    "url": "/merchant/password/code",
    "title": "重置密码验证码",
    "name": "重置密码验证码",
    "group": "01auth",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
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
          "content": "{\"code\":200,\"data\":\"\\u9a8c\\u8bc1\\u7801\\u53d1\\u9001\\u6210\\u529f\",\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/01auth.php",
    "groupTitle": "用户认证",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/password/code"
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
    "type": "put",
    "url": "/merchant/timezone",
    "title": "切换时区",
    "group": "02common",
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
    "filename": "public/api/routes/merchant/02comon.php",
    "groupTitle": "公共接口",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/timezone"
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
    "url": "/merchant/company",
    "title": "获取公司信息",
    "group": "02common",
    "name": "获取公司信息",
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
            "type": "String",
            "optional": false,
            "field": "data.id",
            "description": "<p>公司ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.company_code",
            "description": "<p>公司编号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.email",
            "description": "<p>邮箱</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.name",
            "description": "<p>公司名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.contacts",
            "description": "<p>公司联系人</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.phone",
            "description": "<p>公司电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.country",
            "description": "<p>公司国家</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.address",
            "description": "<p>公司ag_address</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.lat",
            "description": "<p>返纬度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.lon",
            "description": "<p>经度</p>"
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
            "description": "<p>修改时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.web_site",
            "description": "<p>企业网址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.system_name",
            "description": "<p>系统名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.logo_url",
            "description": "<p>企业Logo</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.login_logo_url",
            "description": "<p>登录页Logo</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.country_name",
            "description": "<p>国家名称</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"id\":3,\"company_code\":\"0003\",\"email\":\"827193289@qq.com\",\"name\":\"\\u7ea2\\u5154TMS\",\"contacts\":\"tms@nle-tech.com\",\"phone\":\"17533332222\",\"country\":\"NL\",\"address\":\"1183GT 199\",\"lat\":\"52.25347699\",\"lon\":\"4.62897256\",\"created_at\":\"2020-03-13 13:00:09\",\"updated_at\":\"2021-06-08 06:52:08\",\"web_site\":\"https:\\/\\/www.iconfont.cn\\/manage\",\"system_name\":\"\\u7ea2\\u5154\",\"logo_url\":\"https:\\/\\/dev-tms.nle-tech.com\\/storage\\/admin\\/images\\/3\\/driver\\/2021060411165760b9ef89b1065.png\",\"login_logo_url\":\"https:\\/\\/dev-tms.nle-tech.com\\/storage\\/admin\\/images\\/3\\/driver\\/2021041606031960790c878e268.jpg\",\"country_name\":\"\\u8377\\u5170\"},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/02comon.php",
    "groupTitle": "公共接口",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/company"
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
    "url": "/merchant/statistics/last-week",
    "title": "上周订单总量",
    "name": "上周订单总量",
    "group": "03home",
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
    "filename": "public/api/routes/merchant/03home.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/last-week"
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
    "url": "/merchant/statistics/last-month",
    "title": "上月订单总量",
    "name": "上月订单总量",
    "group": "03home",
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
    "filename": "public/api/routes/merchant/03home.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/last-month"
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
    "url": "/merchant/statistics/today-overview",
    "title": "今日订单情况",
    "name": "今日订单情况",
    "group": "03home",
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
    "filename": "public/api/routes/merchant/03home.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/today-overview"
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
    "url": "/merchant/statistics/period",
    "title": "时间段订单总量",
    "name": "时间段订单总量",
    "group": "03home",
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
    "filename": "public/api/routes/merchant/03home.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/period"
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
    "url": "/merchant/statistics/this-week",
    "title": "本周订单总量",
    "name": "本周订单总量",
    "group": "03home",
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
    "filename": "public/api/routes/merchant/03home.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/this-week"
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
    "url": "/merchant/statistics/this-month",
    "title": "本月订单总量",
    "name": "本月订单总量",
    "group": "03home",
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
    "filename": "public/api/routes/merchant/03home.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/this-month"
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
    "url": "/merchant/statistics/order-trail",
    "title": "订单动态",
    "name": "订单动态",
    "group": "03home",
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
    "filename": "public/api/routes/merchant/03home.php",
    "groupTitle": "首页",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/statistics/order-trail"
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
    "url": "/merchant/api",
    "title": "获取API对接信息",
    "name": "API对接",
    "group": "04config",
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
    "filename": "public/api/routes/merchant/04config.php",
    "groupTitle": "配置",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/api"
      }
    ]
  },
  {
    "type": "get",
    "url": "/merchant",
    "title": "个人资料",
    "name": "个人资料",
    "group": "04config",
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
            "description": "<p>货主ag_address</p>"
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
    "filename": "public/api/routes/merchant/04config.php",
    "groupTitle": "配置",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant"
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
    "type": "put",
    "url": "/merchant/api",
    "title": "修改API对接信息",
    "name": "修改API对接信息",
    "group": "04config",
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
    "filename": "public/api/routes/merchant/04config.php",
    "groupTitle": "配置",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/api"
      }
    ]
  },
  {
    "type": "put",
    "url": "/merchant",
    "title": "修改个人资料",
    "name": "修改个人资料",
    "group": "04config",
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
            "description": "<p>[必填]商户ag_address</p>"
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
    "filename": "public/api/routes/merchant/04config.php",
    "groupTitle": "配置",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant"
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
    "type": "put",
    "url": "/merchant/password",
    "title": "修改密码",
    "name": "修改密码",
    "group": "04config",
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
    "filename": "public/api/routes/merchant/04config.php",
    "groupTitle": "配置",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/password"
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
    "type": "delete",
    "url": "/merchant/order/{id}/remove-batch",
    "title": "取消预约",
    "name": "取消预约",
    "group": "05order",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>订单ID</p>"
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
    "filename": "public/api/routes/merchant/05order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order/{id}/remove-batch"
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
    "type": "put",
    "url": "/merchant/order/{id}",
    "title": "订单修改",
    "name": "订单修改",
    "group": "05order",
    "version": "1.0.0",
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
    "filename": "public/api/routes/merchant/05order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order/{id}"
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
    "type": "delete",
    "url": "/merchant/order/{id}",
    "title": "订单删除",
    "name": "订单删除",
    "group": "05order",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>订单ID</p>"
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
    "filename": "public/api/routes/merchant/05order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order/{id}"
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
    "url": "/merchant/order/bill",
    "title": "订单打印",
    "name": "订单打印",
    "group": "05order",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id_list",
            "description": "<p>订单ID列表</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Param-Response:",
          "content": "{\"id_list\":\"123,124\"}",
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
            "type": "String",
            "optional": false,
            "field": "data.template_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.id",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.is_default",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.logo",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.sender",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.receiver",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.destination",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.carrier",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.carrier_address",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.contents",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.package",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.material",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.count",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.replace_amount",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.settlement_amount",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.destination_mode_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.type_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.template.is_default_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.order_no",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.mask_code",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.fullname",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.phone",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.country",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.province",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.city",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.district",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.post_code",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.street",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.house_number",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.replace_amount",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.settlement_amount",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.package_count",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.material_count",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.order_barcode",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.first_package_barcode",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.sender.first_package_no",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver.fullname",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver.phone",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver.country",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver.province",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver.city",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver.district",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver.post_code",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver.street",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver.house_number",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.receiver.address",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination.fullname",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination.phone",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination.country",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination.province",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination.city",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination.district",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination.post_code",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination.street",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination.house_number",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.destination.address",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse.fullname",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse.phone",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse.country",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse.province",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse.city",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse.district",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse.post_code",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse.street",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse.house_number",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.api.warehouse.address",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[{\"template_name\":\"PrintStandard\",\"template\":{\"id\":3,\"is_default\":1,\"logo\":\"data:image\\/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZUAAAB\\/CAYAAAAn819rAAAAAXNSR0IArs4c6QAAAARnQU1BAACx\\r\\njwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAGtsSURBVHhe7V0FfFTH9n41KMWKQylV6u76f6\\/t\\r\\ne\\/XSlra4u7uEkGDBg7u7u7u7O4GEhIQQd3f5\\/uebu5tswibZ3Sx+v\\/b8siR3Z+aOnO+cmTMz\\/4IO\\r\\nHTp06NBhJ+ikokOHDh067AadVHTo0KFDh92gk4oOHTp06LAbdFLRoUOHDh12g04qOnTo0KHDbtBJ\\r\\nRYcOHTp02A06qejQoUOHDrtBJxUdOnTo0GE36KSiQ4cOHTrsBp1UdOjQoUOH3aCTig4dOnTosBuy\\r\\nSCUzLgYJa5frootZiV04G8kebshMTUVmZqah11iHxORULDtwCbN3nMXCPRexSBdddLlvhOP2Rkik\\r\\nYTTnjSxSSbvhDf+3qmry7nO66JJDbr5cDkE\\/fYVktwtCKhmGXmM9+s7fgxI\\/uqD0XyNRptYoXXTR\\r\\n5T6Qp\\/4cgSd\\/H44Nxz0MIzlv5CCVwM\\/fQMCnryHwy7d00eUW8Xu1IiJ6d0R6UICh11gPeiv\\/DFmN\\r\\nEkIqzzWeiOeb6KKLLve6VKk\\/TsmWU16GkZw3cpIKlYcQS+DX7+iiyy0S8Nnr8BNPNnbRbGTExRp6\\r\\njvVw8w3Be+1noEKdMXix6SRddNHlHpdnGoxXpLLttE4quthT\\/u9d+L\\/3PIJ\\/\\/BKp508DGemG3mM9\\r\\nVhx0Q4W6Y1RnNdeJddFFl3tHdFLR5baK\\/+uVEdqiLtL8fA29xza0m7QFJWuOxAviXpvryLroosu9\\r\\nITqp6HL7RLwVNQ32WkXETBmFjKiCo0HyQkhUHP6vx3yUqzPabEfWRRdd7g3RSUWX2yucBvvgRfn8\\r\\nNpL27UJmSoqhF1mPbac9VWetLPKCmc6siy663H3RSUWX2y8kltcrI6ReDaRK3ykMHOftQam\\/R+GF\\r\\nJuY7tC666HJ3RScVXe6MSH\\/xf60SIoc6Iz081NCTrEdyWhp+cl6Kp\\/8ZpcIXzXVqXXS5nUIvmUYN\\r\\n5dlGE1Cp7liUqzMGZWuPRvk6o1FVlCr7pnom13dvhxjLwzyfEWVeTsrBslSQclG5m5blTpTn7pPK\\r\\nV29bJ+bSKKyYyyc\\/MZeGJWIurfzEXBr2FnP53ibx\\/\\/Bl+L\\/\\/IhK2bURmUpKhN1mPU1d98VKzSahc\\r\\nb6zNg4QDrZooBAoVgzVi\\/F5uUmMQgVHZ2FVM8rBF7F4uM3nYQ7T0b1Mdmoi5vC0RfpdTr6XFUy75\\r\\n10j1s1rjCfiw4yx803sh\\/uu4GJ91naOepdFT6m\\/tGRJOVekvhck7t7ANuHeLJMZ8itcciQry+bVW\\r\\nU\\/GtwyJVlv\\/2WYy320yXcTIGpf9yledcUfofV236mPWcK017yd0jlX+\\/B\\/8PXobfKxWUBVuQ+FWv\\r\\nAL83qmr5\\/t+75tO0VpgOld3bz1pcDp4iEPDFm9aVQfIIEIXKBWuzaZrKqxW1srz7nH3fNbcY3\\/29\\r\\nF+D\\/uply2FH4Pn5vPIMA5if1F\\/zL10i9dA5Itz3MeO7OsygtA4mD1VzHzk9eEuEArGgQWnbWCL\\/D\\r\\ngVO92WQ1MF+WnxXFKnzil6FqJ7E9hbuTmSfzI5FRkViqDF5qOhlV6o3DE7\\/ap1zF\\/hiuIvDK1xol\\r\\n5Rmnkaso1cIoS7YFhYquWI3hKPKb\\/eswS6T8T\\/0xQnkWL0mbMV9zZbpVpB7lfUuKYqZR8fvA5Rix\\r\\n4jDWH\\/XAOe8gBEbFIi4xGbEJiQiNjsPVm6HYc95bHVXSedo2fNV9rvIcuIn3mYbjC1df7HPy\\/fK1\\r\\nR6G49I2PhNDaTNyMBbsu4OiVm3D3D0VkXDzik1IQIT9vhkXh9LUAFZY\\/YuUh1HRZiVdaTEbRX4eh\\r\\nbK3R0n4aiZvLy1a5a6RCizWk7m9qc1zMpNEFy8RRiBrihADJN+CTV82mabUwLZGI7u3M52lGolz6\\r\\nKqUYYOm7Mw8hoZB6vyF6zFCzad4i44YjrH3jrO\\/fkqYdRCv\\/u4jo3ELyG2G+HHaUyAG9EPTTl+p9\\r\\nuNs+0qET0kOCABvPBksVQmrguk5ZX9ZYXBxAVC5NRq\\/HuqNXseG4u02y5sgVvN9+pliKY8QSHIef\\r\\nnZdizJpjcFly0G4ySKTT1G2oMWC5sjj5nrQ0y4pSp6VJBWPuHSn8eyUZ2D84LcHo1UfNpm+t9F+0\\r\\nH+0mbUUNUarvtNXKw02pbIOqDUku1k9H0tujfN93MQZI+kOXHzKbtz1kxIojGLz0oLTVEm1KyEx5\\r\\nTIX1+6y8F9\\/v1RZT0WX6duy76KMIxBqECNGslz7TbcYOvNlmmjrKpFLdMVbXF40ERj\\/S+2G7ztl+\\r\\nDv4RMciwcgyd8QzE3B3n8Eu\\/Zar\\/0uuq1kj6k5k8bZG7QypisfJ8qKhhAwwpWobMpEREjx6Mm6KU\\r\\nAj97vdBWvFKs33yI5BNHDDkUjFSPK0KGvyJALHxzaeYW5hHw+ZuIHmHduyZsWoOg\\/3yAgI9eNptu\\r\\nYYXEHPTD50jat8OQ4+0FF+hD6\\/yijvah+L9TDbHzZyAz1vbd9l5BEXhLlBsHhiUDgkqCUwVvtp6O\\r\\nwMhY2EZnUIN47RF3pdw5gIrUGIahYrneLiQkpsA3JArHr\\/opxftRp1nqPSqKYsrrvenR0DLuPWeX\\r\\nIRX7gAeE8vgcv7BoHHf3w5RNp\\/BTv6XKcyktyvdZscRp2Zsrkznh87T++8zbg7QM28+JsxRJqWkY\\r\\nuOiAqh+KuTJpMlm8p7HKw6Bnsuf8dSSmpBpSsR30YoYsPYC3205Tnk8lTuEWQC70hPlM6X80Y2LU\\r\\n6mPijSTa3H+NSBRvZv0xd\\/w9ZJV4PqPxtBgs9DzzM1YskbtGKn6vVrCaVIjMxASE9+6gppKYjtn0\\r\\nLRRFKv9+H4n7LR94Ke4kld8Q8OFLZtPMLVmkMqSvIkWLkJqChNVLECSEF\\/DxK2bTLawoUvnvJ0jc\\r\\nuk4atPCDpSCkXLmEkH9+1M6Lk\\/y52z7wq7eQfOYEkJ5meMpyGE8\\/Xn\\/UXU09sSOb6+CmwjUYTiUd\\r\\nuOCliMHWE5TPeQeqQcNd\\/kyXU1TOC\\/YVepBbApY5SCzf8euP4S3xXkqJYsq9tkO5XaRiDgminDjd\\r\\nw2kYTolxWsVSK9w4pddj1i7EJlnnAdiCmIQkFUFozNdcmV4UpUpDhc8MWLwf\\/mExhm\\/bD16B4XBa\\r\\nsBevtZwqfXIUns+H4J4TRf\\/UnyPxQ98lOCpEnppm+7RxXthx5jp+E4+YRMexVBhiua88FQUZVGkB\\r\\nNxHyx7fqbCmz6VsoVpOK5J1y9bJNnkrUYEdkJsQbEsofmSnJiF+56M6Qyua1hdo7YilykAqNAfEy\\r\\n\\/V6thPDOLaQ9\\/VXd2gqHuTuVC8\\/BZ66TU2iBPfnHCFESB5CabrtFHJOQjO8cFqm5eVqQVOh3klSM\\r\\nIClevBGMv4euUvnz\\/Vge4\\/veSVIxIjYxGSsPuOHL7vNQRoglv\\/YwilG5d5uxE1Fifd9u0MLvM3e3\\r\\nytMsqUgdcn2HZR+79hjCY25vmY5e8UPdEWuVt2aOWOiJcu2ECt8rKNLqqS5rkJicgkkbTwrRTVN1\\r\\nkLsslsp956kQmWLZJp06IorpPfi\\/KxavjdNgOqncXVJR\\/eCVCmpNJyPa9t32MYlJ+Kb3Ajz9t6vZ\\r\\nTs6BWaTGcNQYuEKetd0a5jpOL7GoH\\/1xcFbad4tUjOCCbIOR69QCuqnHcjdIhaDSu+ATjN\\/6L0NJ\\r\\nqReWKT+r914iFZazSv3xSikOX34AYVFxhm\\/dPqRnZMJ19VFVDySW3OXhFOt\\/pG+7+4UiQ5693UhK\\r\\nScPHnWdLvubHkiVy\\/3kqBlARxq9eqiK3bF2410nlLpIKyyHE4v9RdRWBlrh7GzKTbVf4By7dVAqM\\r\\nndnUYqdwYZS\\/uySWva2WHr+17tRllPlnpAya7DzuNqkQUfFJal68xJ8js9ZY7hapEMqL8gnBH4OW\\r\\nF3iszr1EKvw3p0fbTd4Cr0DbjRxrcFEI+P96zVd9KveUIb0Fhs7vOO2JlFTrp4itBadWR685ojyj\\r\\n53KVxRq5Lz0VIzITEhA11Bl+r1e2qSw6qdxlUqH85334vVEFQX\\/+F6nenqqObYXrqiOi9BkmqVl8\\r\\nVPxVG45XMfxzd55DWiGmvRgq+m67GSj7j2sO0roXSIVK\\/MrNULzReqqadmL57iapECzTMXc\\/fN6N\\r\\nU2GjpK7Meyv3CqnQK2Dgwyed52DX2cKd+mAp2B+5ZsPFevZT03qhFPttOPrO2yNltn1PlzUIjIjG\\r\\nx51mq301pn3cWrl\\/PRWD8kmPjkJoy3pCLJXM55WP6KRyD5CKQW4+XwqRLn2QHhZqM7Fweqr28DVi\\r\\nafE042yF33LcRrH0bF\\/c5JRAfdc1eKLGsFsG271AKhwLXLxdvPei2uTGMt1tUiESUlKxZP9FpWQq\\r\\n1TM\\/R38veSoMj3aYswcBEbZHJFqD0x4Bap+J5qXkrBdunnyv3UxcvRlms3dtDZhHv0V7Vbh9Yfet\\r\\n3NeeioIoktTLFxH0y9daRJG5\\/PIQnVTuEVL5v3dVPakw48VzkWFhPZkDQzbfaDUFFcX64zXEn3ed\\r\\ngxshUVlGiLWgNTlr2xk12J5tdKs1aSupcC6daZtKeiFDahnZxN3U3FfBRV+G+N5NUiE8AyLQ0HWd\\r\\nKkvuuqPcK6TCaK+PxErfdOKa1e3AtmOYdXySiPzk5xQh+fz6HL\\/DtmFEIuvAtE4ojMLqOn0HwmwI\\r\\nFKARpMoiksDyyL8LIqab4VEqEo2hxYWJ\\/KLct56KKTJTpfJ2b1en4XJTpaUL9zqp3EFSYb3l46mo\\r\\n9ZX3X0DQtx8h2e28MhZsxcLdF\\/C0DEoq122nvAqlrI+531QDhQuX5qYEbCEVlofkt++SDw663ciS\\r\\n057+CI9JQERsolII1tJgsiiP0WuOokTNkWrR11pPhYonNiEFodEJCJcyGIXliYpLUsrJWsQmpqid\\r\\n5ZziYV3lrr87TSqxQrxO8\\/fmIBVtD4grag1bjQvewYYn84eRMKi0j7jdxKQNJzFs+WGMXH0Ekzae\\r\\nwJbT1+AXFqPa0dwC+\\/nrgep4l8p1b60X1gkV8+aTHki2cC2F5SFRhUnbLdh9HsNXHFHlGb\\/uBGZs\\r\\nPY1rfuFSv8mS3q3jigYON9oW+3O4Rfu9CpL731MxICMhATETRmrRYJ+\\/bj7fXKKTivWkwnfIiI7K\\r\\nlphoiz4nXziNkFo\\/qftVzJKKQfxeq4Twjs2RHnBT1betaDFuE7rN2m7xoDSFUWFQIf6n18Ks8GFz\\r\\nA8gWUmH6Tcesw2O\\/DFWKjdMN\\/Mk1kV\\/7L0Pd4asVOVApUVFYCpLVaa8AVS7u3bGWVDh377L0IL7p\\r\\ntQB\\/uqxATRH+\\/GvwSrQcvwlzdpxTBEPyyc8KNwWfPe7ur8KMuZEwd\\/3ZSir0BBigwPKw3JYIiTEw\\r\\nIhYOc3J6KgwfphJ0EA+GdW4p4pJS1ObP11tNRZFfh6p+QuFnrs\\/82m8Zxkg7uvmGIk76ktFbYNl7\\r\\nSxl4XEy1hrd6KTwIkmtRHkIEBXkYRvCpU9L2zPOpGjxSZ4SaBi76+3A8\\/vMQvNl6mjoNYe2RqwiO\\r\\nistBLp4B4VleijWnU+QlD4SnYgSVV3i31vB\\/8xmtfObyNhGdVKwjlcyMdMQtnY8Ix66I6N1JSWS\\/\\r\\nntq\\/HXJ97t8LEX26ZH0O79JS5Vdgu8jfuf8oeuJI1Z6WKq\\/cCIyIQUR8gs3fJxkNE2uPocj5beSz\\r\\nlVQajFyDR34crAagUUgERkXACJxvey\\/EGa9AdjuLwMdCY+LVPprSf4+0mlSCIuPwz5AVePS7AVlK\\r\\nie9G4b85RdRs3Eb4hEUZvmEZGEnVdOwGdbxI7rq0lVROXwtEz1k71XE7rSdstkg6TN2KVhM2KoJj\\r\\nOYwKlF7dKy2miEd1xuJd82zDJXsv4bUWUw2bPbXQdabH6aNqjcYrj5EeEKfV6Dl4+EeofnXk8g11\\r\\nKgLb3Nz6BT3sX\\/ottYrguHG0tnhaxaXv5N4fRIOIGyzZjjzB4K\\/By7HxuLsiWe7Z6rtgj\\/zNfreq\\r\\n3jVSsbenoiANne7vi5DaP2sbI\\/OxiCk6qVhHKhlpKQh36Iibr5THzZfKildRWXkWPN2AEXj5fWbo\\r\\nd6AlB3FyGoynFXzyChIP71VTm3catA63nriG8mJtUtHnFwljK6k0G7teHe9iLk0KFS0t3u\\/6LFIW\\r\\nuaXgfD6PcmHa1pIKp73okVAZ5p6SofLjGWcVxJp1nL\\/bql3dQRFx6twwToEZvQOj2Eoqqw5dRfVG\\r\\nE\\/H4Dy5qvYukl\\/tn7t+RrNlWfA8qfiMJVBEl+E7bGVh9+IrFU6X0PDpM2aq8DR51b\\/pOpsJ346I7\\r\\ny\\/Cj8xJsOOaOzlO3qXPZ+O7mvkODot6INareLMUpjwBpswmGY3JuTdMoLCv7FQ\\/IHLzsIA67+apz\\r\\n3Ng2hV1LMcpdI5Xb4akQmWlpSDpxyDBHzxsHzeRvEJ1UrCeVyCF91RSWmsYyk65dRNqOYcY0DtJ8\\r\\npGMWYk3EFngHR+HTrnPVESj5EQrldpEKhYvtVACrDrkZvlkweLbV+A0n8Jh8z56kYhQqC07NXPKx\\r\\nbO2B4Dz\\/qFVHlbLJrUhtJZXNQvpck+C+EtP0bBGe8fV+h5nqgFFLSSUoKg71R65V9fGckJu5dI3C\\r\\nPvScvCfLavQG8qpfCgmw7aQtqj0sxd4LPlpZcpG2OWF5SCI0mBjUQs+moH5ujTxYnooBGYkJiJk\\/\\r\\nAwEfVUfgp6\\/maR3rpHKPkgpFvBqeZhwx0EG71Evq\\/k6AO9RbTtikrFpzAya33E5S4am0xaQc3H9j\\r\\nKTi9Mm3L6dtGKtxPwZN2Vx26YvhWweA0y7TNp9T+ody7xgtDKpxCYhiwaXq2CEnlPSGVtVaQCtdF\\r\\n+i88oLwcKucXLFDmFHoSfOf8FsR5xUCrCZsRYgWpeAZGqPrguhUJzJKwYLYxPS17rKOYyt3zVKrf\\r\\nHk\\/FiIzYGESNHKTCVBV5mCmHTir3MKmIsO642z5+zVJkWHoYZyHARfEFuy8oQslvSsNUbiepUDFQ\\r\\nwTMyx1JwP868XeduG6kwrJrWLevJUvC8NIZlP99YW78wTe9eIBUSw5ttpmP5wcvSByyf1rviG4bG\\r\\no9eLEh2niKVqw3GKOC1R6PkJz7H7Z+gqBFgx\\/UUw6utNLrjXGa2UOus6r3a8nXL3PJVXbp+nopCZ\\r\\noSKIwto30Xbcs7y5yqGTig2kMriPlKm6Vi6ukVgqZspQoHAK8+1nEfTjF0i+rIUZUyHfLpzxDMCr\\r\\nLadkHetibsDklttNKlwsH7z0kOGbBeN2kwo9ldekjhbtuWj4VsGIjE1SIbe8m8RepLLpuAc+aM8T\\r\\nDkapNCwVc+\\/Fi7NelN8zFDhWCNAa+IfHqIMneR+Muo1UlCnrSOVlofeSW7jwz\\/R8Q6MNuViOQ26+\\r\\naursbcM6Ca8XZp2zPPZaiC9IHlhPhQOX6yvJZ06KUvpSu0kxVzl0UrGeVKJc+oj38JyqT04vsnyK\\r\\nZPj5E\\/nMn2Y+s83NlaNAMayvRHRtjbTAwp1mnB+4H4OXYRUTgjA3UPKS20kqnP56ssZwTFx\\/wvDN\\r\\ngkFSmbPz7G0klQnquJrtFigMI0KiEtSeCXoEVG6m6fHftpAKrzx4t9VUlP5jmFKeao3A8DO\\/z8YN\\r\\nrKbTT1xT4BRYVymDT7B1kW1GXA8Ix0LxFDh1ymgv5kPFSo9XTUeZ5FeQkJg+6DBLnUJtyyGS3K+0\\r\\n74IPBi7ejx+EnF5uPlltsmR57gS53FeeiiIKw2dLwUMKE7dvEvJ4Tyk403LopGItqaQibt0KRDh2\\r\\nRmSvDur2xoh+PRDZt4v2ub98djR+7pn1ObxrK5WXTcQipMI6pLcZM30CMuLsf4QGN7ANXn5QzWVb\\r\\nO+BuJ6lwcJYTS3zHGcsVONdUuHfidpAKLe8qdcei1tDVQsKWz\\/f7h8ei99zd6v6Z3AvJtpLKSY8A\\r\\ndJ\\/JkOINyjLnHgyuQ5h+bief22Z93qo+\\/+i0BNVFyZoemMg2p3f6h8sKnPYMNORgG+KTU3BKPF6e\\r\\nPPzbgGXKA1LegpCxptCz3z0veZ4kV28cVh66bNOGU1PcDIuWdNzQfvI2vNt+piJYelIsT+62sJfc\\r\\nP55KZgbSoiKQEh5m+IXloCKKmToWAe+\\/qJSpceFeJxXLSYWKMCMjQ4kptfP3GuT3Zj+L9ex9DcE1\\r\\n\\/4fAvHbTFyScBqOnKYZB8tGD4oHaM8w4E5tOeKhBUEkGsrVRMLaSSpMx69Td8fx+buFgp4VbsqYr\\r\\nmo\\/biLhEK0KKRQkNX3kYj\\/9mG6k0G7tBbeDjgjIteAqVIcvDu\\/nfazEZy\\/dfNnzDMrj7has7Q3hU\\r\\nS27StpVUCK2PUbi4TjH3ObtV+K8Z286qjX657wuhsuUtossPuqlFeHvAJygKi\\/ZcQJuJW1SkGtde\\r\\nKCTngjyXUtJ23MAbFGmZzrAE+y7cgMuSA\\/jeabHKn+tAJBf2OXNlsFXuH08lPQ3JJw4jatRgpMfG\\r\\n5FBaliAjMkJZzuqofMP6ik4q1nkqtiLV1xvBf31vO6lQOA0m3kpIgz+RascwY9\\/QKHzVfb7acGZL\\r\\nnD4HpC2k0jBr86OmaLJFBiQVT6Px6jh77na2Boy0ajRqvSqTtaQSEhWvNhPyHpSqUgZFJlwfEGLh\\r\\nFMq3vRdg\\/q7zVh3DzuCHvRe88YEoVW6ezF1\\/hSEVWzArD1Kh8O6S9pO3KjKwJ7jzfsdZL3Setl2F\\r\\nLjNvruPk57VUlLp6uelkHLh0Qx2Uak\\/w1snpW0\\/j535L1XoLpwVtXf8xJ\\/ePpyIVy4u5VDTQ2uWK\\r\\nZKyCKKF0UW6hTf6G39vVVPl1UrkzpJJy3aNwnopRvngTN1+thOjh\\/ZEeEqzaozDgFA53WnOzma1h\\r\\nlbaSitOC3Wo64nvHRTnkJ+claOC6VkXyRMRaZ6Vy\\/v28d7Ca3qECZxSRNaTCKK3pW06j6ZiN6Dhl\\r\\nG7pM34EO8rPv\\/H1qY+A1\\/1DDk9o7WIKwmASMEM\\/JdI+GqdxJUuGm1hlbz5glFRoUVK5vtJmGxXsv\\r\\nWbyz3hrwoMddZ6+rAzZfkjai8s3PS2A4OT1VHohq6XEt1oBTY+PWHdfOIKvP9aac6122yv3jqQgp\\r\\npFw6D\\/+3qiL4l6+RcvGs4Q+Wg8oz6fB++f7\\/qRONFan85wOdVO4XUuH6Chf+338BCZvWIDO1cOVe\\r\\nuOeCmmbgFIC5wWGJ2EIqhKdYi1wXuHg9MIfwoEmeNmwL6EFMF6XJ6CFawvS+rCEVEgU9CwqnOdMo\\r\\n\\/LeNCo0HFfIdGcnE\\/RPmPMF7hVQo9Bye\\/mc0agxcjkOXfW\\/LXfCEX3gMXFcdxbvtxGupJ16p9KHc\\r\\n+1Y4DcvABrbjxA0nxMDgkUOGBOwMekN1h69RhggDMUzLYYvcRU+lvPWkcvmCUoY82yu0aS2kB1u3\\r\\nqMZBk5mYgPjVy+AvCo6n4gaK4tZJ5T4hFRFGkrHd4lYuVvVUGEzbcgrlRIkUZiDZSir2BhVOSFSc\\r\\nKMRlhvv6J1rtqdgTrAvuOh+09IDyUjiNZq7+7iVSIenRWufel5bjNuC0h78KfLgdoC6au+Mc3m07\\r\\nXSnh3GUxlkdNg8nPxXsvKM\\/aUg\\/RWviFR6P52I3KW2ObmCuPpXIXPRUbSUUUExUip7C4uZGHDlqL\\r\\n9MhwRA3vp4XFfv2uTir3CakEfPEG\\/N+uiqhRg5AeF1voAca77bmIyrsrqNjy2+Wcl9wrpJKSloGl\\r\\n+y8qhchFds6RW+up2AtsF54KPG\\/XebwqCpxlymu96l4iFQrLyWgtnjLMtSkef8I1kduBNPHkeCMp\\r\\nzx2jV2JujYXl4dlhb7aejmlbT8MvNEZNc94OhETHodmYjer2ULZJ7rJYKvefp0LF9MWb2jTIZ6+r\\r\\nU3OtngaRzpV+0wfhHZrA\\/8PqSNi11fCHAqCTik2wC6nI9xhkEdroT6RK27E\\/2AM8XvyrHvPUwDU3\\r\\nQAqSe4FUtLWUEHUVLk8CpiKiUrB2od4e4JQXb06cue0s3m8\\/Q0UY5UUolHuNVCiKWBpoxPK942J1\\r\\nGvHN0Gi1B8je4A7+IcsPKUMgv2nYcrW1IAfHeXtxxjMQsYnJhTaqzME7OFIFrtg6Hij3paei5fs2\\r\\nAj54CUH\\/E8V4\\/JDVFcyw1KRTxxBS+xfEr19l+G0B0EnFJtiDVPxVW3+m1sTYdvYcUBuPu4tym6gp\\r\\nQDODJD+5m6TCOiCh8L6OBq7r1F38xiieO00qPDMrOj4JZ72CREkexCvNp6CMgeDuN1KhGImZl73x\\r\\nBIFu07dh5xkv+IZE231KjLvyf+u\\/VG1QzCsKi+Xhmgf30\\/BY\\/Hni4Vz0CVF1bm9y4QGmvNOF72+u\\r\\nLAXJ\\/empGPOVdALefQ6hjf9Gqpf2AtZUcGZ8HBJ2b0PSkQOWfU+eeZhJxdbOm3rjeqFCilkHJJXY\\r\\nRXOQmWTbInZ+oLLpv2gfShuO\\/LCGWO4WqbAtuIjOaK\\/GY9aLZemqld2gwO8kqbBb8OKnuTvOouag\\r\\n5aoc3PeRH5kYpbCkYtV4l2dnWkgqRmHEGo0NrjV81nUOukzfjnVH3XHNP0JNi7Hv2DouTDFz62mp\\r\\nr1vPRjMV1if\\/brya+U+XlZi06SROuPurPUY0MOxRFpJmjYErsvKxVu5jT8WQ1hdvqtseI526IT0y\\r\\nwupK5cVTmYmJkrwF0ymS9sNKKqzXDHHV04ID1X3zqe5XRNyQ6nHV\\/M9r8vOq9jPp0D4E\\/\\/4tAnkI\\r\\npZWkEiDt6\\/fmM4jo2R5p0fbdP2CKsJh4FfVj7UC6G6RC5XEzLAZbT3mqWxm5JsQbBE2V+J0kFZbn\\r\\nys0w\\/C1KruhPg9U+G0sIhWIrqUTHJ6s8ef3v5Ruhymq\\/JD+Nn\\/mT\\/8792WXJQbzaYopSfObKY074\\r\\nLiQX3q9Db+GN1tPQePQGdRPmCQ9\\/dZtkYSPFfIIi8T\\/HxSqgoaBd9ywPy8+rGbhh95veC9R77Tnn\\r\\nrTaaalNjhoRtBEPLS\\/wxssBj\\/c3J\\/e2pGESdmiu\\/j104SylkW2ARGT3EpELw7K+YhTMR0qwWwlrV\\r\\nF2mA8HaN8\\/jZRD0T3r4JQpv8o97FFi+FwRTBf3yH5EvnkGnnTWC5ceyKH95qM01dq2qpt3KnSIXd\\r\\nMzQ6Dp4BEdh73hs9Zu+Usk6XgT9CnS2VW4nfWU8lU129zLUHKmxa9qZlyU9sJZXDbjfRTBT7b\\/2W\\r\\nqk2itNr\\/Grwq6zN\\/8t\\/Gz3\\/L55rymbc+8kw1tpu58uQnrGOWlVNDXHPglOkPTkvUnfdbT3riapZC\\r\\nt74nkJS6Tt+hFLI1ipzPM4Sc5Xmv\\/Ux1ajJPhOYVzjweh56ULfD0D1f9ix6auXzzk7tGKnbzVAzp\\r\\nBfC2wO8+RuKhvYYv3AY87KSSnooIp67qgEe\\/16vA\\/+1qBQsj7Bi6zf5iphz5CduUpx\\/EbVhls7Fg\\r\\nLbjTmPPotBgtIRZbSYWbAo+7+6l9HPkJnzni5oddYoUOW3YATUWRftJlNsrVGa3O0mIZzHkFtpAK\\r\\n10VCouOx7+INtU8j0soQ1uDIOKmHvahQRzv+I3eZzImtpLLm8FW8Lu9d7NchSqEWJIxo4noEN\\/nR\\r\\nE7Al0s8orG9+36jQ6b3wGH5e2kUL\\/\\/S1AHUvj7WYuvm0mpazdt8Uy0NPqpxacxmN54WUvnVYiF5z\\r\\n9mDXWW91LbG15MLnf+m\\/TNWZuTzzkwfCUzGm6f\\/OcwhtWFNNudwWPOSkQk8haqhTlmeoPA9LxUwZ\\r\\n8hPmwbtwGDaedhsOkcwLCckpaD52A3jHOxdNC1I+tpLKtlPX8O+e89SUxw9Oect3fRbIcwu04z3E\\r\\nauQRKiQTc7vTTcUWUklOTceOM55qh\\/XnoiQX7Dyn1m0sBSO\\/zl4PxC\\/9lqkjTyw5pcBWUjHe\\/Eiv\\r\\nknVhsZgpQ2GEabL8jBQrVXOkmobkadc884vrTNYoc1589kJj7Zy1HHkYxPR3eQnLU00MIiOZ8rRj\\r\\nnr58SoiO7WMNGo9ep6bYzOWTnzwYnopRuL4iyj7CoRPSQy2\\/7tRiPOSkcicv6fJ\\/61mENa+NVF8f\\r\\nqXb7hA9bCh5H8lW3ucrqux2kQj3DO0+e\\/G2YGvjcx5GXcOOg8bM1FqwtpMJjRNYcvoKn\\/x6Fkr8N\\r\\nVWR2ySfE8FfLwLvbF++9iOottGNPzJXNVApDKva6pCu3UDHzwi1tfcPyqSg+S0+DbcpgAB61w6Nv\\r\\nLMWe8z54p522GdLUm2K7M8SZ9ZQ7z7xEm6qboDwp3o9fc\\/AK5bFY43m2nrhRbaC1logfHE\\/FkK5S\\r\\neKIw1THpyXaOFNJJ5faTCo9i+eBFbSpz785CH8ViK9YeuSqEMUlNbeRHLDaRisjS\\/ZdU2lSq5tIt\\r\\nrNhCKoz64YnN\\/C4JgQrSYc4eIQrrph6pvDpP34YyolwLUoT3GqmwPFwX+nev+eqiKx6jYksblZA+\\r\\n0WTMenhbcTjlYTc\\/dfAmFbKRzBj08IUYON86LFCfedCnNUqe5ML0iv8xXOrMwyrPk6RiSwTYg+Wp\\r\\nUEgsH76slFLCri1adJcV7Jwv7iSprFiIIB75\\/v4LWUrcElF1I3VgriymUihSkTYI4N3\\/7APWiJly\\r\\n5BaV9kfVxSgYr0K+bUWqp7v00+uq39gCnnvlsvQASnJKQ5RKXsTyQJKKvBOtdJIKFevWU9cMT1iG\\r\\n1LQMdZ7U1z3nq3c0Vz6j2EoqG49fwwcdZipPjm1gjeQVXUVlzTPKGAW44qAbRq8+qhb2WQ9Uknnt\\r\\nIcktVORUxrwS+FpAhKHEBYNrWe+2n5FFKmwDBj1M3XwSO89eR+1hq1X5+Tv+zVze5oRpPfbLECze\\r\\nf8GqKLUW4zkNfB+Rym3xVIyiiOUltbGRUUN2w50kldWLEfTtR6JghSC\\/FJKwRJgnCeV2ksqg3sqT\\r\\nUGQnXhTTsVQKJBYpN3fNq+lLnusm9W2LQcCje8J5idigPjYRkzFPzon\\/PnCFCtnlYDFHLBzkDyKp\\r\\ncD8Ep0\\/oCdQbsRZBkdbVY1R8kgp6YP1wLchcGSm2kgr3ijBSj6RPpW+pUNlxOsmctU9PgGUZv\\/6E\\r\\nlCUJEVIe3jDJ8GGGEdNroQdHpcm6yZ0GyYrvQ6XPNSWnBXutug9l\\/TF3vNxMU8pMr7T0u\\/85LsL1\\r\\nQG2rBMOnByzah6+E6IyEk\\/U+Us85yiLCd+H6DKczeXXBCc+bokYtH0+NXNfJd3VPJVsMxBLRvQ3S\\r\\ngwIMiRQSd4pU0lKRfPYEoieMQJTrQESPHWqRxEwYich+3RFc4xu1vpQfuVDJW00q6WmIWzZP1Slv\\r\\nc4zo3hYRPdoVLL07ILxbG3W6dJ5lolf23vMIrvUTks+fVldB2woe3cNFfm6YTNi6vlBpMXT1tVZ5\\r\\nn1\\/1IJMKFZVRwfEeEkaHWQPvoEh1vS43lbI8uctIsZVUuOGvy7RtqDd8DZqO2WCx8L6Y7xwWqkMa\\r\\nc3se9FJ+Fy\\/l1LWcB9V6+Idjxtazilw4PUVCIUExSKB8bW3vSrlao9VifZX6QqBSb8zn5DV\\/qy78\\r\\nmrr5FKoIaZEkeGwL79+fLfVuGkkWHZ+ornLuJe36o\\/NSVW6NYLQyGMtCD44EyOmyj6TMkzedVDec\\r\\nWgou6jPgotxDHf1lRtSUkFj6MRNH2XTw5C24Q6TCfOit8NBEayRD0k8+cxyhrRtoazH5eAa2kAqt\\r\\npfTIMKT5+yLN74blEuiPVOkz4e0b50l26p57KW8cj7QvxK755PNnEPTD54qgeE1C0J\\/\\/VZs1eXOo\\r\\nrZi48YQatFQkub2VB5lUmA6JhRY6j6+\\/etO6W1epUHeeu45Pu8xRSi53GSm2kgoVpH9YtNo0yPtG\\r\\nLBU+P3LlYbwuhoJplBUXwtmWRi8lN3hRlldgJFYevCLewn40G7tRnQv2Rbd5+LTrHHzZdS5+678M\\r\\nbSdvkf5yEu7+Mk6sWL9IEcOHl3ipckh9cNrpZ+cl6sIwc956WGyCEGsARq8+hm4zd+LPQSvxFcsi\\r\\ndf1p5zn4RoiTIc6O83Zj4zEPFYRhDbgfit4ZycG0vSyRB9dToUg+JBUueMevW25IqBC4U6RSCKS4\\r\\nXUSYKG8qaXuTSqGQmqo8HLXuk6tcrCN1KsLw\\/kgT0mI92wKSV1jbRiq0XBGX5KN24zt2QXpMpOEp\\r\\n6xEpyq7d5K3qDnsqHlNiedBJhcIyUvqLMuUz1iA8JgGj1xxThGxuHcBWUrEVDPGdue0M3hSFydOI\\r\\nWQZOW5H0\\/hi0QoXeFtT\\/ohOSFMEcueKn1jq2nPRUl2+dlO\\/y4itbriPmbYzf9Vmk6oPeBZUyw5Lj\\r\\nk\\/L3LhgGzqnJ89eDsfuct5TlmlLmXNPiiQNR4tnYMoU8SYiRUWyWriOZygPtqSiRvPw\\/fBnB\\/\\/wo\\r\\nVvxJQ2I24j4gldTL5xHertE9Ryp894jOLbT2My0X2+ftaghrWRepHlds2jXPQZORmIDoia7aWg\\/7\\r\\nx\\/+9q0WSffqqmgaNXcbTrG0\\/iJKnGX\\/Rfa4aaKbTYA8DqdBb4XrDR51m45Cbr+Fpy8D6drsRgjrD\\r\\nVsv7ck9JznLeaVLhqcCcZqKnYpzaowLkmsNkUaQ8\\/uVugJsm2a9YH6X\\/ccXvg5bjRrB5L+V2g2HQ\\r\\nv4rXRW+Jpw+Ytpcl8mB7KgbhtIr\\/+y8ivG1jKft1Q4I2QCcVm2GWVKj0WY\\/ff47EfbvUlJ+tiN++\\r\\nEYH\\/fk97b9N3NazVBP3vUySePKL6ka1Yf5RhxhO19RXDAHoYSEVLb4JSFC3Hb0RwpHWbUROTU7FB\\r\\n6k7dU59rGuxuk4qK+Ko1GjUHr8QZzyDDU3cGRsLwCYlS6xdceOc6SKX6YzF\\/9zmbduUXBsbyrDhw\\r\\nWZXD1r754HsqBlHrKx+\\/gugRA5ARbeNUiE4qNsMcqbAMqk1mTUJ6bIzhSeuR6uWB4Fo\\/qyNh2Ldy\\r\\nv6syYt6ooqbG0nx9VDvagqSUVAxctA9P\\/j5cLaaSWB4WUlGL9sqin4SFu6yPqGQk3aAlBxSpmE6D\\r\\n3W1SoQf2SovJ8rvTd8VLSZQ6H7rikKoHFaklnnCdYWtwPSjC5nO7CgOGQH\\/dY74KrrDFS6E8NKRC\\r\\noRLj9cFxy+arSCaroZOKzchNKsp7fKcaIvp0Vov5ti6kMwAjckAvtSajggDMvCuFRgVPW4geOTBr\\r\\nU6wtUwu+IZH4bcAylKg58qEiFQrflRYs1x6uB2mGmaV1SAV52jNQHWFStnZ2RNHdJBUSCo+8+dFp\\r\\niVofMeJ2TzkZ009KTcXKw26GI3i0cGaSytRNJ7M2nN6pshC816XDlG2qDCyLadtbIw\\/F9FeWSL5U\\r\\ntjz1Nun0MUPCVkAaQCcV25CDVKQdSAIhNf+H5DMnpENZHupoCiqquCVzhTCkjpmumfc0FXVb6Kev\\r\\nqzt02J9sHbA85JHHcHBxl9MnDwupUPh7ei2uq45aHWJsPMLltVZTFDkZ1xDuFqlQ+TF0l\\/ekjFh5\\r\\nREW3FfYIe0vBxfw1R67gK\\/EKGMTAtSYKP\\/N+\\/D3nva0KAS4suNjvMGe35M+NlXlv9rVEHipPRQmt\\r\\n5A9fRli7xkjz9DAkbiF0UrEZGfGxiOjaSvMYeOLBl28jbs0yZCYmGJ6wHonHDiHop6+0ExTMvKM5\\r\\nYVBAyD8\\/qvtg2KdsAcmIcf+0cjl4HiZSobdSRazqL7vNU+RqLRjWy4uueJIx3\\/1uk4oiNsn\\/9VbT\\r\\n0HTsBrWeQAV7Oz0E7UKz84pQSK6mXgHrl\\/X\\/X8fFirjPegXbFE1mDXg9cfeZO9X0G\\/uzaXvbIneP\\r\\nVF4uh+jRQwwpWgBpZM6dF5pUKJ+9Bn+xWqNcHJEebt2BebyAyhZSiR7mLF++\\/ZYHyxfesZlhJ34+\\r\\npPLxKwj67mMk7dgEyEC73eAZXhE92ylCIbFET3JFepTtYb7pDB9uWU8FYLA\\/mXtHsyJ14v96ZUQ6\\r\\nd0dGnG2nH1PhRMQloO2kLXiyxjAUERm05KDhrwWD6mrd0av3HKlQee0666W+S2vVXLoUlpnEwn0V\\r\\nkfHWEQE9AW4o5SZDkjIVGdPrLVZy8h0YH3mFFNNDoHzcaRZ6zt6FHWe81I56eyJJSJtE3G\\/hPrzT\\r\\nTru\\/35RQTOuXfYN\\/+3vIarXx1N3Pun0vliAwMlZI1A1\\/uqzIev\\/cZbFF7g6piHDhNMKpu5pP5zlN\\r\\nBcp1TyQd2K3dHpjP3Lll8raQ06sI\\/Pf7iJk1UdL3QKq31615morXNVWGxD3b1a5v3o9vPu1cwrKK\\r\\nVR7p2AUpvBFR0jCbvj2E5du9DWHN6yCQ60f5KFuSc9B3HyFuwUyk8Lt8v9zp2UukbrkBMaxtQ3UM\\r\\nS0idX5B4ZD9Sr2l\\/594adVNkgZ+Nz19AzPjhajor8Avr+2DAJ68o7yZu+UJk2uitENwH8HGHWXjk\\r\\nvwPE0tuB64Hh6ncFCfckTNl06hYr1Z5Ci5dHhbSesAnXA8JxVZSSubIYhX+\\/ejMEc3eeU9\\/Nj+yo\\r\\nhKk4eCnU\\/N3n4WVB+hROL\\/E5rq0MXnZQ8hifVQetJ27GxRtBcA8INftde8hVf0nbLxRDlx9Snorp\\r\\n5kcK35mKnj\\/\\/3WsB+i86oHav+0fEFEqh8+IuXprFjZW\\/Dlgm3pEWxsxpRNP8jWKcelKKXuqHJzo0\\r\\nHsXLt87iyo2QQnsuvKmS+1m6zthhIDchdzN7iGyVu0YqtFaDf\\/sPwjs1R1jr+ghr0zB\\/EYUU2uRv\\r\\nLV9rLNO8RNJQivWHL7T0eWNh7jxNpW0j9QxvMQz87yeWE5uhrMG\\/\\/RthrepJOgXkUxiRtEMb\\/6V2\\r\\nlBdYR\\/z7v99DiBBkWJsG2vuZS9MewnoTryLoxy+0aTfWBfMz5BkqJBjWWspgyWfpK6HNa6upu4DP\\r\\nbT8pWS3s\\/+8zJAm50Qu2FeuPXkHVOqPxpgz8eiPWqNsGC5I6w1bhP6K0eGOjuTOo7CW824Mn+TK\\/\\r\\nvww3IeYp8vdaQ1fiW4dFqkx5KTyjkFhIBlyPqG1J+kaR5\\/4Zuho\\/OS9R51wZyYvl\\/HPwCvwtZTD7\\r\\nPXsI0xZhdJO5Y1qMwqk\\/3rHPOviqxzx0mLpV3W2\\/\\/9IN3AyNRoyQRF4kw99zkT00Jh5nvAKxbP9l\\r\\nDFy8X1149WrLqahkIC1z+ZoTloHESyXNHe61pP5GrDiC1Yevwt03RG3M5R1AeU3XJaakIiYhCb4h\\r\\n0dgp3tes7efQRjxsthuVP2+xLKitrZW7Ripqc5qkQYtR3fCnfuYn8gyPHjGXViGExGI+vzykgONP\\r\\n8hROg5lLz95CD8WK8mmbA82kY2+hV8FyGdv94+rmnytQDH2FHivTMvNOFomUQYUZt6itduLbSixc\\r\\nh+Bth0V+G5Z13hKt3YKEA8\\/cgLS3GK1vi0TKbmm5aE2TWBhBZTatfIR1xAVyYzr8Wa3xBIvrrlCi\\r\\n8i5YqZNYWRe8A54eDT2b752WoO2kzXCctwcTxOvgoZbcxc5Fdcqm4x5qAyWnt7hG8dfglcqbY1qM\\r\\n7mJd0Qu0dhGcz5PAqajpuZAESMINRqxV05s8Nmbh7gvYdsozqyycvlsuhOay9KA6qqWVeKz0vuj1\\r\\n8L1YF\\/lNcRZG7h6p6KLLXRYVNPDuc4gaO7RQ99\\/fECvwL7Gyi\\/8+XFnA5gaaLvevkJiNaw78TA\\/n\\r\\ntZZT8HHn2erE4C+7zVUezSed5+CNVtMUCfAZnuOlEah9lLeRjJiu0QAgSZHceWT+56oc89U5ZLyD\\r\\nhaHKxu8+a5hq5DuwbNYSmzWik4ouD69wRz\\/DjKUvJ2zdUKhpsNOeAeDlTjwh1txpxrrc\\/2Kc8iOx\\r\\n8HwuKmlOH\\/GEBQqtfypU\\/p0nEFDhm0vHHkLvh+TAfEhaJAvmz\\/Jwgyl\\/8nf8G8vMZ2\\/nVKup6KSi\\r\\ny0Mv3BTJ6wLSrl+ziVg4n63CjDeeVFNgHFQ6sejysIpOKrroIuL3WiVEOnUr1F6isOgEtJu0FUV\\/\\r\\nG6YsQ3MDThddHnTRSUUXXTgN9rEWZhw7f3qhpsF4dhLvtVDHuOjeii4PoeikoosuBlF7j775ECkX\\r\\nzxWKWLadumaINjJ\\/W6QuujzIYjOpqNDO9wz3lOuiy4MgH1XHzZfKIqTub0jzv2no7daDpxkzlLPo\\r\\n78PUzmhj5JAuujwMUqbWKHUXy6YT1wwjIm\\/kIJWgn75Ud1QE\\/fyVLro8OCJ9mnuXYudMtfn4Gi7a\\r\\n8\\/woXuf6fOMJeL\\/DLF10eWjk3XYz8UqLKdhzzgpPRd2Ffmgvkg\\/u1n7qossDJEm7tyLp1FEhFRuu\\r\\nRzCBh3841h+7il1nvXXR5aGRHWe9sO2UF8Ki4wwjIW9kkYoCB5wuujygom6hLMS6ihE8p4lHd+ii\\r\\ny8MiqeLh82deR8eYIiep6NChQ4cOHYWATio6dOjQocNu0ElFhw4dOnTYDTqp6NChQ4cOu0EnFR06\\r\\ndOjQYTfopKJDhw4dOuwGnVR06NChQ4fdoJOKDh06dOiwG3RS0aFDhw4ddoPFpJKZGonxC+aiwdTF\\r\\naD97eSFkGVpOXQSXve5IMaT9MCIzIx2evjew\\/cJVeEUlGH5rRDrOuF2A49ylaDZNq+\\/W8rPRtFWY\\r\\ncfI6QpINj+nQoSMLqYmROHbxKnZeC0OoPkbsiri4cFwMiTX8K39YTCoZ8Tfxfbe+KNagO0o1d7Rd\\r\\nmvVBqb874rvZ+\\/BQtntaKrxv3sDk1avxfZ9h+Gv6RhwJykUqmSnYfmAXvu7UF8Ub9JQ6640SDbui\\r\\neBNHtF97DD4JhT9qRIeOBwXJSXE4evECek2bjQ+6D0PfracRmGT4o45CIzzAHfVdXPFS59GYfC64\\r\\nwKNaLPdUoq\\/jnz7OeKRhX1Rs62yzVGjjJIqyK35beOShIpXU5ER4XffE2NWb8GW\\/sajQyhFPNXPG\\r\\nz9O34XhIrhEgpLLj0G582a0fSjZzQCWpt\\/ItHPBU60Fos\\/4UvHVS0aED8XExOHj+LLrMXIh3ug1C\\r\\n6cY9UbLVMDhsu6CTih1x4sAGVKzdDv\\/6qz2+mbqrQL1tOanE+aBGjz54XKzlMq2cUKlN9s\\/ybfvj\\r\\nmfbmxfQ5\\/qzYWtKo1w0\\/zD\\/8UJGK\\/00vOIyfiAoNu6Fsyz6o2KYvnm7thBozNuGEGVLZeXgPvurR\\r\\nH6VbOKp6rNjaESXaDkabDad1UtGhA+nYf+IIfuk1AOUb9UD51s5qXJXrMBJ9tl\\/SScWOOH94MyrX\\r\\nbot\\/1eqKX8UZyDD8Pi9YQSo3UNvRGcVaOImS64eqIsafVTuYJ5RsyX7+mXbOKNGoO35fdCwfUklH\\r\\nekYGMjLTLToV0xz4LZWGiF0h5WGaWvksL1vATU90HjMOTzXsjirt+qFKWychlf6oOXcXzkekGp4y\\r\\nwApSYRlYFlvrieB31TvJT9tTyYlMSS8tXdrP8O\\/7EXwH1q1tN7DYBrYFhfnaD9IK0hbWjIU7+u7G\\r\\n\\/ifCn5b15UzsO3oQ\\/+k+EKWaOqKKGiN9UL7jSLjsvYzwNMNjVkDVO+vJovwNzxvKbe9+bkzXFhTm\\r\\nu+Zw7dROvNCyB\\/5Voz0arjpd4LtaTCpUdO6+PjjifgNnrvtlyWmvm1i9bgmqNu2Ww2Op0rInivec\\r\\nhjlnPHM8Tznq4YMrIVEmhctEWlI49pw4BZcla9B06kLUcJ2B30fNRK2JC+Gw9iCWX7yBoAJ7eCaS\\r\\nkqKw9\\/BhtJuySUvDdSYazliOmceuIighXf6ehEue17H3ihcOeXjjgPt1HPS4Aa\\/I3Ivl2UhOTsTV\\r\\n656YvfMoOk1fh1qjZ2ppj52PJrM3YPyWQzjsEYjwpFsbMi4hDhe9b2DVvgOoPWQUSjfvY6ijfijb\\r\\nxhlfjVmKGcfdsf\\/qdZy6EYLwRHnJfEil+7azCEpJhZe0xZQNB9F44jKtLBOWot+mk7gYEgNLxlNA\\r\\nUBDWHTqMHgtW4u8xs1Uaf46dg\\/ozVmPg8gPYcsZd6ivJvFLJTIKv\\/00cuuqJg1J\\/rMc9Up\\/XpA6T\\r\\nZVAeObgL7SbNxX+HTEGHxduxzj1ABrkMVsPXzSIzEec9\\/bDHzUvaw1tL080TV8MSFdnlRDr8Av2k\\r\\nDT3Vc5Td57xxIzbt1g6fmYBTHr5ZzxmfPR8UY3jAFMk45e6LMRt2of4YrY0pNcfPQ+PZ6zF18ykc\\r\\n9AwxPFt4UCkFBfpi9ZGTGLx0jcrzL8nLmO\\/fE+ah1YwlGL95L7ZeDYbZHippJCYk4ISMyz2G+jjo\\r\\nLnKN4yUDsbEhmLl8EWqMnIFakvbIHcdxLjghl+KWcZMsaVxyw5BVW9Fo8gKVvxp\\/kxahw4KtmLvr\\r\\nLC76BVnUtwpGJkKjYrD91EUMXble+vB8\\/CN5Mc8\\/Rs9GnSnL0GfVbmyVMR8mYzYn0hEeGSpt6oVx\\r\\nK1bjk24DUK41DVaNVMq1G4rmi3dj46Xr2HfVCxeDIhGXKj1PxpRvSAQOSz3tN\\/RZ6oDLwdFIkL55\\r\\n4exxOM6cj5+GTUXzuRuw6Nx1BCak3NJnoyTvvadOYcDCXWgyfgH+NLRV\\/emr0H\\/LMWy97Ie45Dxq\\r\\nScbNjZu+OCDtxDZiGdhmZwIikcJj5RMTsOPYcRXM9KehPhrOXIuZx6+q8ZOfMs9MiMdm0Xutpb9Q\\r\\n5\\/G7zaYuwvTDHojO4DfTcdrjphpTxnHAz2bHTC4EXjmED1p3xyO1e6PLXu8Cn7ecVAS0ItIMLGgU\\r\\nWqOXj23DCw07onhLZ4PCFFJp0QNP9Z6FEzGa15HjOyJGayA9PQ2XLx7GXyMmo3yT7krplm7hgJLN\\r\\neuOp5o4o2Vw+N++Lcs0c8dHwxVhy2kd97xZkpCEs4Aq6uI5ChWa9UKJxL5WGJg4o26o\\/vh2\\/EguP\\r\\nnUSrwcNQoXFvVGrVFxWa9saL7Qajz47LhoRMkYGgEF8Mnz8br3dwQpnGXDRnsIEhXVU2B5SWNCq3\\r\\ndUGNSeuwz8tE6WRm4OKVi2g0YJhy0Su1pceWTbzPiIdXuY0TKrZyREVJ+4tBs7H2SpAaAOZIpVyH\\r\\n4Wi1co8ot834oc8gPM05ZMmbZSnVtCeebtAL7wycjyVXAhCfR8tnxgdjzZY1+KqnC8qJhfe01Hep\\r\\n5sb36S359cHTUl\\/l5fes7zmn\\/RGVlGtgJwSi+8QpeKFpH1Rq6YgK8p3ydQai9Yoj2LRrM95v01fS\\r\\nExKUshX\\/uwNeHbwUV6Li8yeV1CgMmzYF1ep3lXZxRKXWfVG+dme8M3INgpJTc3Tk9Ihr+N3RBRUb\\r\\n9tLaUMpavlZn\\/L3kGFJlcJoi3v8C3mvjIO3cU6XJn+Xr90bfQ7450oz3v4hOI0ehIutR6qK0sU5Y\\r\\nt6peHFGmmbxva2fUmLUb7uGFm7xNT0\\/F2q2r8UYnZ5SVelL9XvLJagtDvk\\/LWGB5KopX+9OsHTgb\\r\\nnbNhMyUdb7djeL+t1AHfUequQhMHvN5uBqYduIhxc2ehTN2u2liS\\/lKxSV+023je8G35floS\\/K6d\\r\\nRIcRrqhoyKs0+3VWGaR\\/y3fLSHtW7+aKTiv343JY3gZYgUiJxemTe1GbY0L62dNNZazKz+JSB0+x\\r\\nv4iUknKUlt+Xa9kPv0\\/fjAO+UYYvC9LisGrHJnzW2Qnlpd9VbqeNI21MaTMiFcVYKy91V0HKX3\\/+\\r\\nDpwPFRJNCMWoRQvxqvTXilLXFaU9qQNqzdyERdt24JfeQ1BK6q04y1KrE94cMB+7boRmk2hmMjbv\\r\\n240fnIejSrMeMt4cRA84oITUDcvPccPfscw\\/T9uMjT6xiM\\/NLUnB6D1hIp6X50zHTeOlRxES4I6e\\r\\nY8fjWUm7uPy9hJSdbfZ0s55K7\\/w8dz9uJKaZHUMBvp5oO2wkKon+LN4ku\\/88Jek8LXrn21l7FIH+\\r\\n0GugGitqHHDM1O4Ep8O+oosNCeWBFL9z+KpDbzzSyBmDT4p+KgBWkYo50OI5d2QzXhJSKSueSqX2\\r\\nA1QDG0nlaGTeJU5LS8W+3evwZovuKNawLypJZ+DUUPmWDnisXhc8JgqmeEtOt4nybeuE4o2649lO\\r\\nYzD0SC5iyUxHaIg3Og0fjpL1OqGcSscZlaWDVWrtgCfrd8aj9aXCRSm803cMPuw1TAap5NdWm4ct\\r\\n324I+u5yMyRmQGYafH2voPPo0SjduBtKt5L0JM1KYg2VkMZj+Z5o2MPw+37SmfqguBDZ50PmYu1V\\r\\nf0MiwNnLF\\/C30wCUbtJDymTs\\/NnC960s5SDxvT90IdZ7hEret5IK36dKJxe80GMEqrVzQgWpIwY+\\r\\nMG9N5O+izEs07oGvx67Cft+IW7yMlCg\\/jFswB6+1EsXJziXfe0bSKikk\\/Hh91nd3lGzBQAyWqa+8\\r\\ndw9U7DgKo475IDzFpDsnBsFx4gRUat4TFaQeuT5UVurh04GT8LnDUJSRNmPalSSNJxqLEttyCWFJ\\r\\nBdi4QsCXT+7Ca006owyJQspQpmkPlO8xDbsCk5Bm0o3OHNqEatK\\/1Fqd1AGljLRJld6zcVksW9Me\\r\\nd3L\\/ejxbrx3KCBnwuacbdUElp4W4aDLir189jh97OKFY\\/R6oIH1Hq5e+eEr6jeqHDeT3qu36oUIr\\r\\nBxSp3xNvDFyE4\\/n07fxAQlmzZSXKNOyMojLwn2FdSfpVpL6elDpnnmyP4i2cpA\\/LmGL7Sr97sqEo\\r\\nl3n7EWWSrUYqR\\/BRO1GG0nZ8R75D5S4T8O\\/Bk\\/FGB0fVNuxj5aReX+g9DWt8tNv70lKTcPrsYXzX\\r\\nox9KNuymxs0z7Z3FyNHGzGNShiKieMtLW7D\\/lRUlWLI5Ff0m24glIwVnLxzHX30HoJQoygpsOzGW\\r\\nSFwlpOxPy09+ZlsxP\\/bx0k37ovbs7ThvJPH0BGzevRmfdXFG6ZZ91bSXuTFFI6xkiwGouWA\\/zoUm\\r\\nSp8NxbiFc\\/BiG6mP1mLISZ8tJ\\/3nPacx+NxxBJ4V\\/UIyqizj4Ukh3kYrjuFatCHPtFgsWrsc77R3\\r\\nVDqE466KPFdOCKCIYdyUkTSrqP7RB0816I4X+szASo8I8YK0JBRSQtBj7BhF\\/uVpOEk6xZs74VvX\\r\\nRfhn5FRJT0hfjEwan9njWtKUeihaqzvabrooXlfOUZ0ScQOthwxDSSlHeaX35DtSB+yn7EfUe0Wk\\r\\nDj8SvfdlR+oMjktpY6mHR2r1gvPBgkkFsT74tbsDHqnriMmXIw2\\/zBt2IZWzhzfhxTw8lSMR5kuc\\r\\nKZ7F9UuH8VpTUc6NhTikMp4Vi6NcC7HO2g7ANyPm4ZtB41C1lTBus76o1nGAqowywsDVuozD4mvZ\\r\\n0xeMrNq7ZwMq1Rdikw5p7FgVZCDy35+4zMLX\\/UajcoteKEYrQYiPeVFJ0GWu0GEonHdfMaSmITQk\\r\\nEMOmT0fpusbBJt6CNHjJFv1Qvdd4\\/G\\/wRHzu7IqqbR2lc2tTWgxGKC0W3XdjRKkHaIOOpPJH34Gi\\r\\nELqrwf5MB410jVKJnVu+X6xRT7w7cA7WXQk0SyrG77EzPCUK4LE6HPQku77KUjN6QFWkvkp3GY3h\\r\\nRz0Radqh0+Mwf90qvCmKq5wMYL77s6JAnmrUG9V7T8R\\/Bk\\/F\\/4aIFSXKlMqDSq6qDOwSklfpHpOx\\r\\n2D0E8cb+LKTiNGkiKtLqk07KfLmuVqGNkKsM1OKNuuJxKd+jv7ZEqW6TcSgoOgcp5IX0qOuo3bc\\/\\r\\nnmzGwSR1QwJvNQDDTwUhOd2YQDImz52KYg0MVqrhvUmCjzcdiDHnw0ymy9IxceE8PFGne5ax86h8\\r\\nrr3sRFZ5MiXPXx36o6gYCOwzqp5b9sDjTfrhQ5e5qp2\\/7uuKsk206d1nO3A9TPISxfHWqA3Io3vn\\r\\nDSHPCO8zeFXI8zFJo6zUGdOtKkqKCry6w0R8M3IevnMZj5fFO67QsndW21eQti7TejimnA82JCbJ\\r\\nkVSuHMUnHVhX2eOvqoEcaDw8Xq8rHvunI4rV6ooa8\\/cjgXWZkYqbNy6KweMiddlLq0t5twrsw636\\r\\n4VMZM6qPO41C2ea9UJH9QfoMialE60Gos\\/QA\\/HMtBRaE6LAADJ87WwwsUZ6SnlLOnYagzuyNWHfR\\r\\nG9tPnUe3KbNRtXkPFJXxUFQI9tG\\/OoiSdkS3DccRzPxEwa\\/buQHvC1kWF0+ZCjSH9y9Cz7+UKOgn\\r\\nGjjgzzm7s0hlwqK5eMGgE1SdSp9lGdhnSzbujifqSpvUaI0yncdj8WU\\/JKq2TcXGnZvwgXi7ZaUu\\r\\nSRzUHSS\\/al1d8fXAifi38yjxKB2VnlDryzJuiooh8vKgJdgfGINUYx8RUnGaNEGMZhKKNm4olVnn\\r\\n4lk9ThIQ7\\/xx6YsV2CeYFp+Rn2VkTD3vshxe8ck5vJWVa5egrOg9EgqfZdnKi56jLv73cOm\\/Qyap\\r\\nvvuk6CXj3\\/mTpPhInT7oJx57gaSSEoZ6fZ1RpOUwrPcvwDgU2JVUniKpGAZA\\/qSSicS4ILQRhi0i\\r\\nFagqToRWS5mOYzHwwHVEJiQhLCoa2\\/duwlutxN00kIVa6BcX77NJm+Bt6NSR0eFwHD9GFENPg+VC\\r\\nF1is3fYuaLXuFHziEhEUFoLlm1bhVUmL3oUxz\\/J0BXORSnpKHA4c3oF3xforZSBKWj5Ptx6IGrN2\\r\\n4tTNMETFJyJQiGfu+lV4TYiljGFet6y47uXaD0PPbefUFJSbuxtajhyF56Qxn+80SBozO2\\/Kcx0H\\r\\n4uWuQ1BN6u3rMcuw4VqYVI8ZUhHhd2nFfTR0Dpw37IPrspX4L6eBpGwkAeMzTwnxddh4CjdM5qMv\\r\\nXTiG7x0GoIwMBioQKp2SkvY7w5djydVABEodRcXHY9e+rfhPbxKEZiVT2RWVzv7nnB24blx3MkMq\\r\\nFHpLxaTzfj95NcbtOIg+C9di4r4riEqyUPuIx7ls5QLlIdFCY8d\\/olEv\\/LH4COJTtHfJjPbGb0IC\\r\\nTzbhANf6mlEeE+X50\\/wj2VNgqRFoOtBFlLVG+s+2l4EkLvzUy8b1vHQsXj4XRf5pq96Vz7APFm3i\\r\\ngHqrzyIwPkm1c2RsGMbNmoxiogyz8hNiebS+A1xPihFgBbgAfvbwFpRr3FUpjXJUAOIVPFGjDX6Y\\r\\nsw\\/uweGq70fFxcHb4yQ+bi9WvKG\\/Vpa6Lioefd1lh7OmZfIiFRppFeRdqnQZhh5r9mHy9v1wWrYF\\r\\n27y0qaSY6ChMmT8bxcWzp4LPGjOdR6D9xjO4YegPnjeuwXniRJQTYqms6luIRQy\\/V3qNxYKr0let\\r\\ngKeXB5oPGynjV6vHMtJ\\/nus9CeMOX1NrCqlp6fC8dhndJ0\\/FB04T8OuE5Wg7fzMGbTyM9VcDEJEs\\r\\n7Sqeyo4D2\\/E\\/x8FqrGcFAMlnbWz1U+Ps5S6DUa3zEDRedgAXw5OySOVlMZroeRnrid\\/hFNYXrgsw\\r\\nePN+DFi+EWN2noFPZLzqI0Hel1BTiLek6JyKhjXj0uIVP9tnGhad9UFQTILonyBMWTIPL4knQ8NR\\r\\n6SDxfIoIsdRffhQBcYZt3kIqzmZIhcZosZZCrov3YNLuI+g5bhyqCYkZx7R6Rjyg0l3GY5t\\/Qg6S\\r\\najJgkPRveknaWGD\\/LdHSCV13XEGoasNIzF+9ABXEqypnKD\\/FKlJBIroOdkHRViOwP6zAh+1HKsbp\\r\\nr6xC50Mq3E3ufekgqrcVL0RFk8l3pLGLNemFusuPi8uYzcWxsVEYM32SWB3dDNYmp5qk8bqOwxy3\\r\\nCGVxBficw\\/fdtcrUKkysD1G+H49bi8vx2kIUyxkcHADnCePwZCNRWoa0zHkqYUIWg6dNRXFRblVE\\r\\n2TO9skIaX45agYMhyVmWMNMMCfKFy6zpKCkuLQdnFbF8Ssk7\\/XfiOpwITUFaagrCIiNx+NwZNHId\\r\\nI3\\/LXqgvJ4qbCnijRwhuhkUhMCoOiTKwzJEKFRCtmbcGzcGKi75ISElFYnIcFm1Yg\\/c4L28gXZJF\\r\\n8RbOaCCK52qkccogHkvWLcfr4u2VE8uYll0FUWbVHKZg\\/eUbygswtlJaairmrV6O6u1l8IlLzzTL\\r\\nNZVB5DQXW30jkMIH8yCVUo274S2XxTgdEInE1DQkJKcgSdx1Q3VZhEif03i\\/dU+xvIRUJM2nGnTG\\r\\nx2M2ZK2rXD29G1Vp6bKvSdvQmzF6IWVEUVfstxCXErUpsHi\\/8\\/iifc8sD7qUkE7l\\/ovhx4VbQWak\\r\\nF\\/7u3VeIxtAHRWgt\\/mfGHsQIiWUXOxNJieFo4DwQj4kHo56VvIvV74RPp+62+mSIpPgYnPP2w6kr\\r\\nbli5ey+mrtuEPnN3wkfI17jWmCFjxMfjFH7o5oCiYpEzT3pSJeXdf5q5C\\/HqKSlZHqRCr76keHlU\\r\\navHSV5JUe6QilRokMxW+vpfxe58BKNLUWVmv9JjZx2svPqQI3PjuXEd18\\/LEP\\/2Holhz7d055Vmu\\r\\nzQC0Wn0UsdmVVCA8PN3RZJirWoNgOhwvlToOlrGyApOPuOGsjz+i4xIQn5SESCHzmMRkxEkfSkxJ\\r\\n0xayVV7pSExKREBEJJZs247PuvXH09IPVXrSF8p3HIF260\\/hREA0boRGITQ2ASnyDrd4KoZ6KiMe\\r\\nzSv9Z2O9200kSN9PSGGfTTO0QwbWbduAN0U30ZvhuKks9VS8pYsYvh4INvQz1T8ib6DTqFFqjdRI\\r\\nBuWa9MArgxbghDF4Rkig17ixYnhmkwrrntOpfyw6jBvRiVo7xfii1dDhauzTuOJzJIsnW4\\/ArCuR\\r\\nSDawQLjXKXzYWsjCYNBSnhI99Pqo9QhPMBpymUhOjkSnoUNQRLw\\/43ShdaSSjmGTx+CxrlPhZUFn\\r\\nt\\/\\/0lwWeSqoo2oWLZ6F43WwiorVdtvM4zPfIGZWTnpaCM8e349lWvdVcO59lw3K+tM\\/OK8hMS4b3\\r\\n5UP4WAZVScOgomKvKB3nt\\/n7YHqwQGJcNNZtXolnJK2y0kn47K2eSoayzuoPHIxiQj4qP3mmrAyi\\r\\nX2dtx1lRmNcCw7PksqcnRi9ehGeks2pTBNJRxRt4w3k6llzWpilYRz7e7mg7aiyKixXMNEkqZdoM\\r\\nwu+zxfMJTVYDRhs0\\/MKtpMI1pVKtBqLh0oNwi8q2\\/C+eP4ZfnAeL+21UPOKpNHdG\\/aWHcMVAKhmx\\r\\n\\/hg4Yzqq0hI1dGZaWx+MWI5Nl31xPSj7fSirNq3BG536qcHHTkj3vETrYRh+1EezFs2QCvOlguqx\\r\\ny73g9ZN8kJkcjhaDRYEZpsCqcCqk+zQcCE8RMk\\/H5LnT8GS9HspCpedBUjFaYFVl8D\\/WwAkTL0bI\\r\\ns8Dx\\/evwVOOeqk34d66NNFx3Uf2NCPU4ilcbd5K2lUFrICauabTddCZHfWgSBofRI\\/Fog2xvhSRW\\r\\nqfdsnDFqeEshDZ0hIzldiCM1LQ3JVGZCKFHhgbjk7oHJGzaj1rjZeKP7IJQ2tAHHFUmllJDKjzN2\\r\\nQVsVkaTMkYryUvrieYdp2B54a0BBRkoizp\\/ag7fbcLNgNqlU7egCxx2XRBnn7ONHLl1FK9exyhhj\\r\\n+uyLZeR7307egLNR2QZgQQjy94ajWOqlGndXRp1xqoiBISWa9cGLXYfif0OmoM7kxei6dDvWnL2G\\r\\ngNhby09kZmZoIcW9B2aNEWNIsfPuywgW5cdxl6V9zHgq2lhxQpOVR+ARZUZbJoZg8IypeI7rLSbj\\r\\n5qVBi3DgZnjONcvMNMxbuRjPyVhR09ysJ9FpT3ceg5mXQhHH+VZz01+teqFYB1esuB6B7ODRDCxc\\r\\nvgBlOatgeE6RSqvhmCGGtJFUfC4cwAvyfRqKKi2Rxxr2RP2158RQNG2XTGzeuASlGnbJ6ufWkQqw\\r\\nZoWUp+88hFjQ3HfFU0mWTu0ycSwerautb3CwlBWXunzv6dgRnEshyaAJuHoEH5kMGloDT4s72nj5\\r\\nEfl7Cm54nFB\\/N5IKoxu4btBy3ZkcVmR6cjyOHd6G14XdS6lnzXgqGcm4eOUEvuvBRTSNxKiUqnbg\\r\\nfpwBqNbJRWSQEk5dvSDW\\/3PyOWv+U6SMKPjXHCdj3llfLU3BDZ+cpMJ9KiRJqzc\\/rs+5T8Xr6hnU\\r\\ndHEVa81YVs1TMSWVmMBraD1mvCgOJ6WotXKKUmbZOw7C8x21aTjjez0rn42KWJVVBl\\/JVv3Qc9cV\\r\\nFZZt1lNp2QtFxCiY5xaevfZiEzKxbesKVGqmrTdUIVE0HYCpl8RLSgrCrw4D8KRY7iwfrbLa09ai\\r\\nUf\\/BWntK+3BxsubSE0iTkTJ\\/ySw8IYOM5SP5FOk4Fmv8hMANOR3du1bNoxsHmvau\\/fBcZ60eKC90\\r\\ncM76zDoyWo6Ucs17olSXidgabNsLJyclYLl4KkNWbUGtsbPwThdnsdwHSf8R70Q888dqd1aEyfVE\\r\\nY9ksIRWuhZWTPvjphM24aYbfUxLjsG37WlRo2iPnu6s+IX1B+jj79PPS59V78\\/3l3dlntHJonvtn\\r\\nrvTcLffTMlLjsPPANhmr0pfpfRkNUHkvTp2WaemAJ5v0UuspxcQY4NTzm30nY\\/j+SwjMlY0ilWMH\\r\\n8Y0ZUjG7+dHcmkprBzzVYSSGHPFEuJkZ2uRQL3RwHa1FZxr6Ob3xj0etxumg6GzCMmDrbtGDMq4r\\r\\nGDwhZYxJmww86CnGmPSRXNNfJHPOApQRvbczKDZ7WkuwZtNKlBFdkUU+LcUjkf4708RT8T6\\/XwXd\\r\\n0KtXz4iQVDrs5HRiztJdOLYVpZr1ynrOWlI5tHMlPh6zCYmGf+eHu+KpkFQGThitKsBIKpynfb7P\\r\\nTOwMyTlA1aC5dAivdHTOJhXxRGjd\\/DVvH9KEVHyuHhVPhWHDmvdhJJU2689KhWVTK0nl6LFdeKmD\\r\\nU96eSloizpw9gM87imUg1jKfMUp56ZBFxVp7rKFBxPLlzyc4CGSwG6VI3a6o3HE0Rh66lrWolk0q\\r\\nxsGkeSq\\/ztxRqB31ilQGDs\\/XUwn2voQ6Q0aKC093WnuXZ6UMKsqH78P3EOG7MaKN7VJUBrXxfR6v\\r\\n31UMgN5oufY0\\/GJldJtbqJfBUa73NKz0iUKiBdZMfkjyv4hvuoiR0NJJTT9yobnjrmtwu3RELXCT\\r\\nbEgSjzZ2xrLrcdizY5UoYbr2A5T3UM1lBfyj49B75DC16Mn6Lt6gM94auwXhJiNox+YlKCID17h4\\r\\nqdWLRha525hi2saUR2p1VIQ375p1rgqnlI4d249fXSagjFiPRet0VMEozPdRyedftTuJ19cf3w+Z\\r\\nhPcdRqp31drWMlKhp15B2uZ\\/s\\/aYDSRISogVpbUCxZtmKxllOEmfLC1Enfvdn5B+YfreagzU6463\\r\\nnGdhnY9lhwxqyERcdCgWrV+Jt1p1w6N8Txk7T4lFXlpISps2oiGRLaXEcyzfaRQGHbiC6Bz9Kg37\\r\\npQ6tJRVTT6Wq1Hf5buMw8awPYsz02aQQT3QdO148jmxPpWSDLvi\\/CetxKeTWfU7b9mzOQSqaMeaM\\r\\nbjvcEJoo7C6k0jPX9BdJpazDDOzKRSpLVy+6xVMplstTIamY81Q67vK8hVQuHd+G0ox8NBgR1pIK\\r\\nN0C6rD+QHWKdD+zqqeRYCCqAVAbNmIZH62iWkpFUnnGYjnU3c\\/UGGTSB7iSNXJ5K095otOqkjNA0\\r\\n+N84j2+6Gj0VsXrEQuD01x8LD2TNPROcy968ZRWeb9pNRVfw2Vs8lcxUXBJF\\/UOvfjKApEzqfaSD\\r\\ni7J5uecIfNp\\/nFn5zETedxiBr4bOwpwz0mBaqvl4KhsLTyoFeCrRAR5oMTqnp8IO\\/4JY5B\\/0HW32\\r\\nfSjG9\\/nQcRRedZiA3ruuIpAxkmZIpZxYvVWc5mFPkEm0i63IiMPAyROkrrg43F9FH30zfTt6z1ki\\r\\nFmxv1V8Y0VLGcTZCOffucxqvNeuCEmLUUAEX7TwePRevxR89HZWhow0gBww8FpBjAF04sgWl6mQP\\r\\nNPUe0i8+7OOab30Y5QNR+NXFI90caLmnQgvb9\\/JhVKrbAUWEuEmQjCgrKwqupCjWn8cuxsLDp7DN\\r\\nMxThUcFoPWiQMlq0NrOMVJTR1bwPanJ9xPCcKeipbN22BpVFoRk3LDNt\\/ny974QC3\\/0T57F412EM\\r\\nfpyyDjsDcmvvAiD6Ii0lDpd8rmHsqrWoO3gk3u4xBFVl\\/D9JkhaD7GkxJrTZASmb9NNSQmwfuyzE\\r\\nZq9wQyKiFoSY9xy13VPh9Ft56VfPOUzBvEs3VVBNbpgjFXoqn4xegzNmPJXcpEI99FSroRh0yAeR\\r\\nnDbOw1PJTSo8SWTN5hU5PBWSStlO43J4KiEex\\/FuS2nDXKRSY9ERJJmsSxOb1y1F6Yads56zllTS\\r\\nklORnGoJpdjZU7E0+otrKrMXTEdJ6UDGAc347lLthmPW1WjDUxrSUpNx\\/MBmsYS7qn0JTF9bUxkI\\r\\nh72e8kQGwsL90HLIUBmkYnnJ3zlAuMhc3XkO1hpi8vmcf7AvWgwZjidFMWkL9ebWVDJx\\/aYXmroM\\r\\nw5MNNUvOuKZSc+Fu+CamIpGL5PkIF\\/v4Uy2KGuDp7Y5mrmNRQjqKNlV25zyVzIRgDJ89Ey+IlZa1\\r\\nptKoGz4fvQKHfUJvKb85YWCAWixlgvmQyl57kIrg6N4NeEasVFph7CPPd3bBi0KCFdtp7fa4DJ6f\\r\\n5x9UgyczKQAN+w1U02L0bCq2H6jWVxhxx\\/UIElAJGZBHonIepxHjcwovNdKMIWM\\/5KDsvMdLC4Qo\\r\\nQPgMJZdRmC\\/S0uLRd+xoFRCgRS5pCqNC90lYecVfLdSqDcZCPnEx\\/uAhrnxXY7+2hlT+yoNUuOHx\\r\\nyoWD+KA9DTEpg6RdQcZLpY6iAA96mn3X3KLqR8pqzbubggvhXE9KknSCw8Nx8PRpzF6\\/CR1mLMR7\\r\\nPYeK4hUrXdqE5FKWi+kOEzH3jHf2OoaMke0Hd+OLnoNRupWmKK31VCoYSGV+HqTCNZUB06eiCvey\\r\\nGb5TXsry8sCF2OUbjpwzZimYvmwRqsn4M46JZ1r2ROmOrlh6TcYYdXwuUuEzeXoqqwr2VDJjvFHb\\r\\nsR+KGoNHRMpIX6\\/gPB+Ho9KyySL2Buo4OquAD9sW6q3DXfFUGP117cwePN+okzZlpl6yH55u2Bu\\/\\r\\nzNtveEpDbHwcnMePweN1s6fKuBBetedUrLiuDZnEhBgsWjEPj9XqkMPyKi+ewAeDF2DhqWvYe+YK\\r\\nek6aquLLsy1T89FfkeHBGDN3ugxgbUGRz9G6eX\\/IQmy7mXOYpqYmwS84CG43QhAZl6AUgjnQU2k3\\r\\negxKGqYc1FSDvPuv07fgdFjuCWP7eio8HmLpxjV4Q7yUcqJ4OFC5Oap8z8mYw2MlchU5KDQY5\\/3D\\r\\n4RsdhxRGo+XGHSCVjDhf1HEaoAYM24CDgF6IcarqUfn92LMhau1EXhBzFs1Sc\\/HGQcM2pvD5x+p2\\r\\nxrez9iMp9+hJ8kddGWyPNs4elFyof2vMZtxqf6fjgncQPALCEB5nTlVbAlGk0T6o07cfinAaVPIj\\r\\n+RVv2BUfjt8iCjq7fOlpqTh2YCMqiFdtHFf2IhVlYAV6o37fgSjSwFHVEb1\\/Hsr467w9iMlVTYy2\\r\\nuubnp6Z8ohKt9EzMgOM\\/Ni4O1wLCcSUwGmFJ2plfPJ0jRd770Klj+MlxEErKO3CccFrwBSGVySe9\\r\\nTNZI857+6rfnMkJyr5HkXlOR5wskFWnz5ZvW4NU2vdX+HFW3YtCWaD1UvHauL2Zb7unhnmg6eDjK\\r\\nyjhU03hC1CXqd8bbw5fjbFisNmORa\\/rLWk+lXC5PhV7f2FlTUKxul6y1PgavFBMd8+WIJVjtHojD\\r\\nl73RYdgINc1dIUvvsS9ZTiq+Xm5oO2QY3u0\\/\\/Raj3xzuiqdCJCUJWYwbpTb7GL\\/D2O6nmg5C83Xn\\r\\n4B2TCv\\/QQExbOgcVGWPdRmsoWuJPNuiBv5Yc08JbCWkEf38P\\/OEgg7VeV7UArdKTZ8tIx+G+g2Li\\r\\nxXDxs1izvkpJGZWTuX0qmamJOHJiH95sq+1SZr5qoLbuj09dV2LNBV+ExsTC3ec6Rs5fgtel0zGC\\r\\n6C2HUfjf4En4Y+w8DNxxCr4GnU4E3vREr\\/ETpKNke0llRSm\\/NWg2Zp\\/yhm9gEC74+CMgVgatnT0V\\r\\nIsDrAmr2dVGbR5W1L+\\/P4zCe6Tsbww9ehWd4DKKjQrFiy0Z81FWIWwbeKz2G46uBE9U5Qk2XHcSl\\r\\nyHjNUrwDpEKlMW72FG0KzDBgjELPo0r\\/JXBLyD6+JfDKEbWRVilgY38SUXtTGjtjupvpWXNGZGLz\\r\\nhkV4pGbbLEOjqjz\\/WKNe+HriNpwMiEZIbDy8b\\/qg\\/4SxKNaoO8q0G4i3e49EzdEz8bPLePQ\\/4G1I\\r\\nyxJkIiXaG790c8giFQrbtlSHsRh3LgRhMZEIigjBknWLUJ275A1GF8VIKj8JqRiVq22kAsTFRmLu\\r\\n0rnq1ACjIaa+124Qfp+7C8cDYhASHY0LF0+j\\/UhXdXxK5U4u+MxpLH4fOQ3\\/TFiImaaeQwFIT4rB\\r\\nieN70H7CDPw2ai6+7DsKz8l7V241AK3XHMVNk7Fy4fJ5\\/CN92kgqfOd3B\\/AIo5x7gk6eOSHkMwQl\\r\\nmmkHSjIq7elWTvh1xhbsuh6G6\\/7+cPMPQySPGbLFUxHEBXmg4aBhoke4k57BOvK9Zr1QttMoDNl+\\r\\nAR6hMfDz9UL30WNRWTwa414WeilF6jmg375rCOfUF1FITyV39BcRfeMMvu7kgCekjzOQiH2ffZjr\\r\\nZdxwXUx0H4NVqJuNOo9iMamkRqCNyxA89nt7PPJXOxTvNgmnClhGuyueioIQQbjPaXwiA+exetpi\\r\\nqrKY2vLcKAdU7zoEr\\/cYpo4Q0XYyS7pUmA26olqvqdilttdmIz0lEWdPivfT1lHNyxrdekZtlRPr\\r\\nhIuOZduPxMdOo\\/ByR+e811QMiAgLxIhZ06Vs3dQiIvPnOkhpaegKbQfgrV4jUL3zILUpjBsf2eFK\\r\\ni8J+8u920mkGw3H35Rwx\\/NEhNzFsxjRUFO9H22ym5V9J5IUuQ6Tc\\/fDfsSuww1csAXt7KoLMtBis\\r\\n2bIab0l9c\\/cvSYXvRGJh\\/b7afRhe7TYE5cUq1KwtLvb2RpHaHfDoLz1RZ\\/FxeMUYIqfuCKnIu53d\\r\\nh9cNe1a0+tJIgrvif55\\/CIkm88aZKeFo2G+A2glv9FYoZRt3RZm+c3Et2XyhMmJ90LT\\/QBlg3bP6\\r\\nIAdl0aZ9Ua3zYNXOL3XurxaU1TFE0l\\/KSP08+ndbFPl9KOa4W7NQzencKHQcNEjy65EV1aVOi5D2\\r\\nZZ9kfm\\/2HI6Komw47VVciEL1fXnOfp6KIDMNN32viKfWH09w\\/w5JVfLhOkBJMVCel\\/H3Vs9hqN6R\\r\\na4vSR9pIf2glfxNyefTPTni903SsuBapxr9FSIvHzoPb8X7zLnjir\\/ZqrwoNl1LsQ91Go9G8XVi4\\r\\n9zRmbtyMBkNdUUXqmRY\\/11qLNemLBov3wyM2p0vtduksavcfgqdFR2RP62jrJdU6DUDFDsPQcvUJ\\r\\neESLrrDJUxHI+504thsftnNE0cbiOUgbsL3KtRZd0GYAXpFxU73rQCFAbWrZqKeK\\/N0Jn4xej7Ph\\r\\ncVnrqoVdU7nFUyGkfOePbVceLccF1+g0vad5bsXqdEDJjmPxWf\\/xqNbB1ECxkFQy4+E4xhX\\/+q0d\\r\\n\\/lWzDR7tNg0n7gSp8CymUn+1xhNifVG5UZ5q0Bklu0\\/Om1QEGeLqup\\/Zg0\\/bS0f9u72yyqjgeP4O\\r\\n0+BCNl+eRFNGKv\\/Rfzqgao8pWHcjIbuhTJCelgxPj5NoNWQISjfogkfIrpLuk43747MRizDxxHXs\\r\\nObQTb7YWgpFOwco356loyMTNAF90HTcGj0i+RcXDYKehsmXDaeVzUr9T5ZPO\\/0SdLtJgrnA56I7o\\r\\n3JPNGUlYv3e75N1LvCaHHMTC3etPNuyhrDHjMS1b9+3Apx0c1O+ZV8kmPfBkU2e0XXsiB6m4XTyB\\r\\nX5wGq\\/0YfI6h2U82cECt+ftwmTuJTZCRHI5JS+bj+eY9lMLiyQJqEIoiKSsKg9+nYmN4J39XpJ7U\\r\\nYe1eaLLiOHyis0NxSSrdxoxBaSlbyWZamxer1wlP95yGzX5RyEN\\/Ww0SRUuXoeK6S1l5NpTkw35F\\r\\nzyN76isby5fPwRPS5txQy2c5bcLBUGPRUaTlqfwykRx0EV9JXTOaS31P6oD9jp+NQqXF36k0ZXAp\\r\\nq\\/FKdIFTB7nBhfrzp\\/egUp22KjLNaIhRwZCwSPj0Hh6Xsnw5dgP6TZum2oploIFRTNrktRErskKF\\r\\nSSrX3Y7g7Vbdsp7jQYdF6gvxLjiE\\/I4ny0yX8XLtFL7vJkZXrQ6KlGjFV6LFb3hvjhMGS6g+Lsr7\\r\\nX3W64fV+s7D6WpTV754QFYTpSxagvLxf0YY0xpzVOOBucd5s+njtzkJw3VBCCJFjgn3+0bo98H+j\\r\\nVmG3GFu5s0uODsDgmTNQRgzNUtI\\/2EZUpjT+yjeT8dK4D\\/6ctyfHMS3VmonVLsqa78bLAkt3HoMZ\\r\\n533yJhVBpij5zTs24Y02PaU88h0Z9+ocQI4bQz3Rm+aYKc7ouX\\/a4SPX5TgdEpPTkzNMf5WSduIt\\r\\nuFoZuuCxzhOww\\/Q4FwE9lafrdZTnDONLjAlGO06\\/HJ6TVATsU1cuHsJPg8bKd7ri0b\\/a4pEa7cR7\\r\\nccBHg+djtVs4zhyxPaQ4MtQPw2fNxCc8m9DdnMefE3Yhlasnd+D91t3wTNeheKWbJi+0cUCVAYvz\\r\\nJRWCO4cD\\/b0xYPp0sYrEQmzEjtQZ\\/\\/qno5JHZRAVbeqIZ7sOF8vrKE4H5N+ZWZ6kpARxoc9i7u7D\\r\\nmLPzLHZc8EEYj4FIiseRIzvwbGvj5kfxMKRDV203BD23XTSkkA1unYoOD8K8NcvwulgkPJ24aN1O\\r\\nWWWjPMKBwEP3Og7FLxNXY6ObP1LyUGDxEf6YuWwBXmzZC49I53yE31dpdBLXsiOqdhmPGSe9hDDT\\r\\nxFPZh+\\/6uMggGajVZ6eBqNxlNDptPn+Lp1J7+Dg803FQVt0\\/02EQWqw6iqtmNnRlpEZj+66N+MV5\\r\\nqDonrKgMrEdqZb\\/TI3U64fFGvdSmu7f7z8DYw24ISUjJ2ZGEVAZPm4KXxRp8Sbws5vli2z6oPnA+\\r\\n9tvRUyGWr1+CZ8Xrpeeq3k0G88uDl8LNcFKCKYKvHsGnbXuoaQA+qyzKTuOx0CuugIGQicTgAAyd\\r\\nPEZ5QvRuTPsghf+m0cRpsh\\/HSL++GWG1UjWCJ3Mf378Rn9NoaNwjqx8wHwYKVOrggrrzdsM7OQ3h\\r\\nN07js47SR9XYGiJetvTZrhOx3FvzQUgqN64eVcRQxdBXXu7igufFKm2w\\/HDenooBJBb\\/GxfQbeJk\\r\\ntb+lVCN59zom715LSLxuTxSRMfhMl+FotWgHTotVbeu7x0RHYNXmDfhvrwHqMFeelMH+b8yPnx8V\\r\\nxcjIy5flXdqs2KsCWfKyCa5fu4DWrqMUAT0iRp2xHtWYqtkBX7quxH6ecpwcgRkrFuGdLv3xXGcX\\r\\nVU8vSVu\\/0n8OFrjlfaq3EdQrl0WntBg3XZ2PV6JhFzwqxG8s96NChpyue7HXWLTfdAHXY5JuNXwN\\r\\nnko1Ib2XugxWZXixrQOq9luI3cHxWeOGedFTeUba8yVDv3+1kxifnc14KgbwO2npcTh40QsL9h7C\\r\\njG2nseWSH+LTeD8McHTPWjXrYwupSOrampdIXu1gikKTCpGWnIiA6FgE55LA6Dh1v4Yl4EALCbyB\\r\\nldt3wXHxOrSZvRJt5qxCx6XbMf2YO9zE2sjrEqnklCScd\\/fA9kue6p6M6CRRgnw2S7TnUpPisGf3\\r\\nBjzXuEuWp8JD+sq3HWzGU8kGB15URAB2HT+JwcvXoOM8KZuUr92clei9dCMm7T2HwzfDEZeUWmCl\\r\\npyclws39ClzXbdbeUaTb\\/DUYuGo7lhy6AN\\/QGNUZU1LTEBEbf0udxiZx9312JumpKYiMi0OQmedu\\r\\nvYdEA78fGxeBY+fPYczareg1ewnaz1qmytJ70VoM3XYKW92DEJ7IXey3psHvRyckSPvG5sg3NDZR\\r\\ndfgCqsAqZIo3GxKT891iEky8JlNIuSLjEnM8y3\\/nUQ23gAOHVtnyvbvhtGR9VvtQ+O8Jh9xwPjgB\\r\\n6XZ4R1qXyYnBWLpnFzrPN\\/anVRi5+TBO+Edm1TvrOiYhKWt8hcdKnUdGIFLaxoj0NCGf2Lgc7x0s\\r\\nYy9S6skyZCI1NRm+AT5YuXu\\/9AHD+BPpOHc1BqzaiRlHPeEmljejtuzx7pExkThy4RzmbNuGgcvW\\r\\noMNcQ13PW4deK3Zi7hE3uEUkKAMt3\\/zk71GRYdh38gQGLc9usx4L1sB1zX7RCQEIV1c3ZKjjX7gW\\r\\nalpPIdJneZSQJWBbZKQnwuvGdczdtkPqaZXSAcyv2+p9WH74ipBJYp5GJcvAtgyRtjEtQ5iM8+Rc\\r\\n30kQHRYSk7tNY3MGzmQm4PiVG9h9+Rqu+IUghuUTUeVUPw3PCXZsWYbSQoRGUtGmkS0lFetgF1Kx\\r\\nJ4wVYiryfz7IRFhkoHY45d+dlRXymdMY\\/GfQVDSfvAOHwrPthcCQIHRyHYHi4nFoEUX91GL5i32m\\r\\nYf7lUMNTeYNlM18+EcMzliJ3Osa07ySMeZqWI6sshmceRpirE61e2Nvsi9x53e26z7M\\/3IZCaelq\\r\\ncmuehocshLl0tN8ZHrAz7FHmQiPBDz\\/3HoAidXuIFzkUXwycgP8NGIO\\/Zm6HhxBpVnGSA9F2yBAt\\r\\n5N5AKs+06K4OiFwfkPOuInvgniMVWxAbE43R0yfhsX\\/aq3lntUu8fne1z+TLoXMxYtMhDFm7G83G\\r\\nTldzrcboK3WGkbisX49bjauWnD+gQ4cOHfcKxFNxHO2KonU6aettjUXv1e0MBoF8OnQhhm44qHRf\\r\\nK5chKCaEkrWVosMAPPJXW7zmugGR9nZTBA8EqWSkJuHCqT2o1rCTtgHSwMb0RHgAHCvwESGcooZN\\r\\nj1wUZYRMqYZdUa3LaMw4a93x5Tp06NBxL+DY\\/g0oUbcDirVwUkEK1G1cS1THCP3dTq2XcoGfm4JV\\r\\nZJroxafqdxIvZbgFa4224YEgFSI5MQ6bt6zAsw24uNhJHW3B6CxGs3BRisKoFh49X4SL0yqSbCxG\\r\\nHL1hSEGHDh067jckq2CWMg06qahERnzxRkxjBKPaBMyoQvmdCj6RZ0p2Gocx520PNCkIDwypEKkp\\r\\nyTh78Qx6LliN7wdPwKsdnVCtdW9Voazokm3645VeI\\/HX2DlwXn8AJ25knyWkQ4cOHfcrdh8\\/gZ6L\\r\\nVqPGyGl4o3M\\/VG3RXW3RoO4jwVTvNhQ\\/jFkM5yUbVLDJbeIThQeKVIzIlP\\/S0uMRERaE60ERuOIf\\r\\nqsQ9IAz+ETHZZ1jp0KFDxwMC6rS0lCTERATiqp+m84wSIHqPIUt3Qu89kKSiQ4cOHTruDnRS0aFD\\r\\nhw4ddoNOKjp06NChw27QSUWHDh06dNgNOqno0KFDhw67QScVHTp06NBhN+ikokOHDh067ATg\\/wEp\\r\\nlV6aPmJpJwAAAABJRU5ErkJggg==\\r\\n\",\"sender\":\"VAN\\uff1a\",\"receiver\":\"ANN\\uff1a\",\"destination\":\"RETOUR\\uff1a\",\"carrier\":\"\\u627f\\u8fd0\\u65b9\",\"carrier_address\":\"test\",\"contents\":\"\\u7269\\u54c1\\u4fe1\\u606f\",\"package\":\"\\u5305\\u88f9\",\"material\":\"\\u6750\\u6599\",\"count\":\"\\u6570\\u91cf\",\"replace_amount\":\"\\u4ee3\\u6536\\u8d27\\u6b3e\",\"settlement_amount\":\"\\u8fd0\\u8d39\\u91d1\\u989d\",\"destination_mode_name\":\"\\u90ae\\u7f16\",\"type_name\":\"\\u6a21\\u677f\\u4e00\",\"is_default_name\":\"\\u9ed8\\u8ba4\"},\"api\":{\"order_no\":\"SMAAAEG0001\",\"mask_code\":\"\",\"sender\":{\"fullname\":\"EVA\",\"phone\":\"636985217\",\"country\":\"\",\"province\":\"\",\"city\":\"Groningen\",\"district\":\"\",\"post_code\":\"9076TN\",\"street\":\"Hoogeweg\",\"house_number\":\"3-91\",\"address\":\"9076TN 3-91 Groningen Hoogeweg\"},\"receiver\":{\"fullname\":\"test\",\"phone\":\"123654789\",\"country\":\"NL\",\"province\":\"\",\"city\":\"Amstelveen\",\"district\":\"\",\"post_code\":\"1183GT\",\"street\":\"Straat van Gibraltar\",\"house_number\":\"1\",\"address\":\"1 Amstelveen Straat van Gibraltar 1183GT\"},\"destination\":{\"country\":\"NL\",\"province\":\"\",\"city\":\"Amstelveen\",\"district\":\"\",\"post_code\":\"1183GT\",\"street\":\"Straat van Gibraltar\",\"house_number\":\"1\",\"address\":\"1 Amstelveen Straat van Gibraltar 1183GT\",\"all\":\"1183GT\"},\"warehouse\":{\"country\":\"NL\",\"province\":\"\",\"city\":\"Amstelveen\",\"district\":\"\",\"post_code\":\"1183GT\",\"street\":\"Straat van Gibraltar\",\"house_number\":\"11\",\"address\":\"NL Amstelveen Straat van Gibraltar 11 1183GT\"},\"replace_amount\":\"0.00\",\"settlement_amount\":\"10.00\",\"package_count\":1,\"material_count\":1,\"order_barcode\":\"\\/var\\/www\\/html\\/api\\/storage\\/app\\/public\\/admin\\/barcode\\/smaaaeg0001.png\",\"first_package_barcode\":\"\\/var\\/www\\/html\\/api\\/storage\\/app\\/public\\/admin\\/barcode\\/10177.png\",\"first_package_no\":\"10177\"}}],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/05order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order/bill"
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
    "type": "post",
    "url": "/merchant/order",
    "title": "订单新增",
    "name": "订单新增",
    "group": "05order",
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
    "filename": "public/api/routes/merchant/05order.php",
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
    "url": "/merchant/order",
    "title": "订单查询",
    "name": "订单查询",
    "group": "05order",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型1-取件2-派件3-取派</p>"
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
            "field": "begin_date",
            "description": "<p>起始时间</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "end_date",
            "description": "<p>终止时间</p>"
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
            "field": "post_code",
            "description": "<p>邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "keyword",
            "description": "<p>订单编号，外部订单号，客户编号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示条数</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
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
            "description": "<p>返回信息</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.data",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.id",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.company_id",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.merchant_id",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.merchant_id_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.order_no",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.source",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.source_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.mask_code",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.list_mode",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.type",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.type_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.out_user_id",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.express_first_no",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.express_second_no",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.status",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.status_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.out_status",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.out_status_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.execution_date",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.batch_no",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.tour_no",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.line_id",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.line_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.out_order_no",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.out_group_order_no",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.exception_label",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.exception_label_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.place_post_code",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.exception_stage_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.place_house_number",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.driver_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.driver_phone",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.starting_price",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.transport_price_type",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.transport_price_type_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.receipt_type",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.receipt_type_name",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.receipt_count",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.create_date",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.created_at",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.updated_at",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.links",
            "description": "<p>跳转信息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.links.first",
            "description": "<p>第一页</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.links.last",
            "description": "<p>最后一页</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.links.prev",
            "description": "<p>前一页</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.links.next",
            "description": "<p>后一页</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.meta",
            "description": "<p>页码信息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.current_page",
            "description": "<p>当前页码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.from",
            "description": "<p>起始条数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.last_page",
            "description": "<p>末页页码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.path",
            "description": "<p>地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.per_page",
            "description": "<p>每页显示条数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.to",
            "description": "<p>终止条数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.total",
            "description": "<p>总条数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"data\":[{\"id\":4171,\"company_id\":3,\"merchant_id\":65,\"merchant_id_name\":\"ERP\\u56fd\\u9645\",\"order_no\":\"SMAAADW0001\",\"source\":\"1\",\"source_name\":\"\\u624b\\u52a8\\u6dfb\\u52a0\",\"mask_code\":\"C178\",\"list_mode\":1,\"type\":1,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"out_user_id\":\"904566\",\"express_first_no\":\"\",\"express_second_no\":\"\",\"status\":1,\"status_name\":\"\\u5f85\\u53d7\\u7406\",\"out_status\":1,\"out_status_name\":\"\\u662f\",\"execution_date\":\"2021-06-11\",\"batch_no\":null,\"tour_no\":null,\"line_id\":null,\"line_name\":null,\"out_order_no\":\"DEVV21904566802\",\"out_group_order_no\":null,\"exception_label\":1,\"exception_label_name\":\"\\u6b63\\u5e38\",\"place_post_code\":\"9746TN\",\"exception_stage_name\":\"\",\"place_house_number\":\"3-91\",\"driver_name\":null,\"driver_phone\":null,\"starting_price\":\"10.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":null,\"created_at\":\"2021-01-16 08:56:51\",\"updated_at\":\"2021-06-10 11:47:58\"},{\"id\":4165,\"company_id\":3,\"merchant_id\":65,\"merchant_id_name\":\"ERP\\u56fd\\u9645\",\"order_no\":\"SMAAADQ0001\",\"source\":\"1\",\"source_name\":\"\\u624b\\u52a8\\u6dfb\\u52a0\",\"mask_code\":\"C178\",\"list_mode\":1,\"type\":1,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"out_user_id\":\"904566\",\"express_first_no\":\"\",\"express_second_no\":\"\",\"status\":1,\"status_name\":\"\\u5f85\\u53d7\\u7406\",\"out_status\":1,\"out_status_name\":\"\\u662f\",\"execution_date\":\"2021-06-11\",\"batch_no\":null,\"tour_no\":null,\"line_id\":null,\"line_name\":null,\"out_order_no\":\"DEVV21904566802\",\"out_group_order_no\":null,\"exception_label\":1,\"exception_label_name\":\"\\u6b63\\u5e38\",\"place_post_code\":\"9746TN\",\"exception_stage_name\":\"\",\"place_house_number\":\"3-91\",\"driver_name\":null,\"driver_phone\":null,\"starting_price\":\"10.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":null,\"created_at\":\"2021-01-16 07:56:51\",\"updated_at\":\"2021-06-09 19:27:46\"},{\"id\":3495,\"company_id\":3,\"merchant_id\":65,\"merchant_id_name\":\"ERP\\u56fd\\u9645\",\"order_no\":\"SMAAAKKD0001\",\"source\":\"1\",\"source_name\":\"\\u624b\\u52a8\\u6dfb\\u52a0\",\"mask_code\":\"\",\"list_mode\":1,\"type\":1,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"out_user_id\":\"\",\"express_first_no\":\"\",\"express_second_no\":\"\",\"status\":1,\"status_name\":\"\\u5f85\\u53d7\\u7406\",\"out_status\":1,\"out_status_name\":\"\\u662f\",\"execution_date\":\"2021-05-19\",\"batch_no\":null,\"tour_no\":null,\"line_id\":null,\"line_name\":null,\"out_order_no\":\"\",\"out_group_order_no\":null,\"exception_label\":1,\"exception_label_name\":\"\\u6b63\\u5e38\",\"place_post_code\":\"1183GT\",\"exception_stage_name\":\"\",\"place_house_number\":\"13\",\"driver_name\":null,\"driver_phone\":null,\"starting_price\":\"10.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":\"2021-05-17\",\"created_at\":\"2021-05-17 14:18:11\",\"updated_at\":\"2021-05-17 14:18:11\"},{\"id\":3346,\"company_id\":3,\"merchant_id\":65,\"merchant_id_name\":\"ERP\\u56fd\\u9645\",\"order_no\":\"SMAAAKFU0001\",\"source\":\"3\",\"source_name\":\"\\u7b2c\\u4e09\\u65b9\",\"mask_code\":\"\",\"list_mode\":1,\"type\":1,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"out_user_id\":\"904566\",\"express_first_no\":\"\",\"express_second_no\":\"\",\"status\":1,\"status_name\":\"\\u5f85\\u53d7\\u7406\",\"out_status\":1,\"out_status_name\":\"\\u662f\",\"execution_date\":\"2021-04-19\",\"batch_no\":null,\"tour_no\":null,\"line_id\":null,\"line_name\":null,\"out_order_no\":\"152\",\"out_group_order_no\":\"\",\"exception_label\":1,\"exception_label_name\":\"\\u6b63\\u5e38\",\"place_post_code\":\"3600\",\"exception_stage_name\":\"\",\"place_house_number\":\"2\",\"driver_name\":null,\"driver_phone\":null,\"starting_price\":\"10.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":null,\"created_at\":\"2021-04-14 14:28:55\",\"updated_at\":\"2021-04-14 14:28:55\"},{\"id\":3315,\"company_id\":3,\"merchant_id\":65,\"merchant_id_name\":\"ERP\\u56fd\\u9645\",\"order_no\":\"SMAAAKEQ0001\",\"source\":\"3\",\"source_name\":\"\\u7b2c\\u4e09\\u65b9\",\"mask_code\":\"\",\"list_mode\":1,\"type\":1,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"out_user_id\":\"904566\",\"express_first_no\":\"\",\"express_second_no\":\"\",\"status\":1,\"status_name\":\"\\u5f85\\u53d7\\u7406\",\"out_status\":1,\"out_status_name\":\"\\u662f\",\"execution_date\":\"2021-04-19\",\"batch_no\":null,\"tour_no\":null,\"line_id\":null,\"line_name\":null,\"out_order_no\":\"151\",\"out_group_order_no\":\"\",\"exception_label\":1,\"exception_label_name\":\"\\u6b63\\u5e38\",\"place_post_code\":\"3600\",\"exception_stage_name\":\"\",\"place_house_number\":\"2\",\"driver_name\":null,\"driver_phone\":null,\"starting_price\":\"10.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":null,\"created_at\":\"2021-04-12 11:17:16\",\"updated_at\":\"2021-04-12 11:17:16\"},{\"id\":3231,\"company_id\":3,\"merchant_id\":65,\"merchant_id_name\":\"ERP\\u56fd\\u9645\",\"order_no\":\"SMAAAKBK0001\",\"source\":\"1\",\"source_name\":\"\\u624b\\u52a8\\u6dfb\\u52a0\",\"mask_code\":\"\",\"list_mode\":1,\"type\":1,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"out_user_id\":\"\",\"express_first_no\":\"\",\"express_second_no\":\"\",\"status\":1,\"status_name\":\"\\u5f85\\u53d7\\u7406\",\"out_status\":1,\"out_status_name\":\"\\u662f\",\"execution_date\":\"2021-04-09\",\"batch_no\":null,\"tour_no\":null,\"line_id\":null,\"line_name\":null,\"out_order_no\":\"\",\"out_group_order_no\":null,\"exception_label\":1,\"exception_label_name\":\"\\u6b63\\u5e38\",\"place_post_code\":\"6712GD\",\"exception_stage_name\":\"\",\"place_house_number\":\"48C\",\"driver_name\":null,\"driver_phone\":null,\"starting_price\":\"10.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":\"2021-04-08\",\"created_at\":\"2021-04-08 16:05:56\",\"updated_at\":\"2021-04-08 16:05:56\"},{\"id\":3180,\"company_id\":3,\"merchant_id\":65,\"merchant_id_name\":\"ERP\\u56fd\\u9645\",\"order_no\":\"SMAAAJZN0001\",\"source\":\"1\",\"source_name\":\"\\u624b\\u52a8\\u6dfb\\u52a0\",\"mask_code\":\"\",\"list_mode\":1,\"type\":1,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"out_user_id\":\"\",\"express_first_no\":\"\",\"express_second_no\":\"\",\"status\":1,\"status_name\":\"\\u5f85\\u53d7\\u7406\",\"out_status\":1,\"out_status_name\":\"\\u662f\",\"execution_date\":\"2021-04-07\",\"batch_no\":null,\"tour_no\":null,\"line_id\":null,\"line_name\":null,\"out_order_no\":\"\",\"out_group_order_no\":null,\"exception_label\":1,\"exception_label_name\":\"\\u6b63\\u5e38\",\"place_post_code\":\"2153PJ\",\"exception_stage_name\":\"\",\"place_house_number\":\"20\",\"driver_name\":null,\"driver_phone\":null,\"starting_price\":\"10.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":\"2021-04-07\",\"created_at\":\"2021-04-07 13:03:35\",\"updated_at\":\"2021-04-07 13:03:35\"},{\"id\":3119,\"company_id\":3,\"merchant_id\":65,\"merchant_id_name\":\"ERP\\u56fd\\u9645\",\"order_no\":\"SMAAAJXI0001\",\"source\":\"1\",\"source_name\":\"\\u624b\\u52a8\\u6dfb\\u52a0\",\"mask_code\":\"\",\"list_mode\":1,\"type\":1,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"out_user_id\":\"\",\"express_first_no\":\"\",\"express_second_no\":\"\",\"status\":1,\"status_name\":\"\\u5f85\\u53d7\\u7406\",\"out_status\":1,\"out_status_name\":\"\\u662f\",\"execution_date\":\"2021-04-01\",\"batch_no\":null,\"tour_no\":null,\"line_id\":null,\"line_name\":null,\"out_order_no\":\"\",\"out_group_order_no\":null,\"exception_label\":1,\"exception_label_name\":\"\\u6b63\\u5e38\",\"place_post_code\":\"2153PJ\",\"exception_stage_name\":\"\",\"place_house_number\":\"20\",\"driver_name\":null,\"driver_phone\":null,\"starting_price\":\"10.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":null,\"created_at\":\"2021-03-31 10:28:50\",\"updated_at\":\"2021-03-31 10:28:51\"},{\"id\":3118,\"company_id\":3,\"merchant_id\":65,\"merchant_id_name\":\"ERP\\u56fd\\u9645\",\"order_no\":\"SMAAAJXH0001\",\"source\":\"1\",\"source_name\":\"\\u624b\\u52a8\\u6dfb\\u52a0\",\"mask_code\":\"\",\"list_mode\":1,\"type\":1,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"out_user_id\":\"\",\"express_first_no\":\"\",\"express_second_no\":\"\",\"status\":1,\"status_name\":\"\\u5f85\\u53d7\\u7406\",\"out_status\":1,\"out_status_name\":\"\\u662f\",\"execution_date\":\"2021-03-31\",\"batch_no\":null,\"tour_no\":null,\"line_id\":null,\"line_name\":null,\"out_order_no\":\"\",\"out_group_order_no\":null,\"exception_label\":1,\"exception_label_name\":\"\\u6b63\\u5e38\",\"place_post_code\":\"2153PJ\",\"exception_stage_name\":\"\",\"place_house_number\":\"20\",\"driver_name\":null,\"driver_phone\":null,\"starting_price\":\"10.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":null,\"created_at\":\"2021-03-31 10:27:47\",\"updated_at\":\"2021-03-31 10:27:47\"},{\"id\":2834,\"company_id\":3,\"merchant_id\":65,\"merchant_id_name\":\"ERP\\u56fd\\u9645\",\"order_no\":\"SMAAAJPI0001\",\"source\":\"3\",\"source_name\":\"\\u7b2c\\u4e09\\u65b9\",\"mask_code\":\"\",\"list_mode\":1,\"type\":1,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"out_user_id\":\"904566\",\"express_first_no\":\"\",\"express_second_no\":\"\",\"status\":1,\"status_name\":\"\\u5f85\\u53d7\\u7406\",\"out_status\":1,\"out_status_name\":\"\\u662f\",\"execution_date\":\"2021-03-19\",\"batch_no\":null,\"tour_no\":null,\"line_id\":null,\"line_name\":null,\"out_order_no\":\"144\",\"out_group_order_no\":\"\",\"exception_label\":1,\"exception_label_name\":\"\\u6b63\\u5e38\",\"place_post_code\":\"2642BR\",\"exception_stage_name\":\"\",\"place_house_number\":\"45\",\"driver_name\":null,\"driver_phone\":null,\"starting_price\":\"10.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":null,\"created_at\":\"2021-03-17 13:43:07\",\"updated_at\":\"2021-03-17 13:43:08\"}],\"links\":{\"first\":\"http:\\/\\/tms-api.test:10002\\/api\\/merchant\\/order?page=1\",\"last\":\"http:\\/\\/tms-api.test:10002\\/api\\/merchant\\/order?page=4\",\"prev\":null,\"next\":\"http:\\/\\/tms-api.test:10002\\/api\\/merchant\\/order?page=2\"},\"meta\":{\"current_page\":1,\"from\":1,\"last_page\":4,\"path\":\"http:\\/\\/tms-api.test:10002\\/api\\/merchant\\/order\",\"per_page\":\"10\",\"to\":10,\"total\":32}},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/05order.php",
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
    "url": "/merchant",
    "title": "订单统计",
    "name": "订单统计",
    "group": "05order",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型1-取件2-派件3-取派</p>"
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
            "description": "<p>订单量，以订单状态排序</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[15,2,0,7,2,4],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/05order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant"
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
    "url": "/merchant/order/{id}",
    "title": "订单详情",
    "name": "订单详情",
    "group": "05order",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>订单ID</p>"
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
            "description": "<p>订单ID</p>"
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
            "field": "data.merchant_id",
            "description": "<p>商户ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.merchant_id_name",
            "description": "<p>商户名称</p>"
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
            "field": "data.execution_date",
            "description": "<p>取派日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.create_date",
            "description": "<p>开单日期</p>"
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
            "field": "data.mask_code",
            "description": "<p>掩码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.source",
            "description": "<p>来源</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.source_name",
            "description": "<p>来源名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.type",
            "description": "<p>类型:1-取2-派3-取派</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.out_user_id",
            "description": "<p>外部客户ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.nature",
            "description": "<p>性质:1-包裹2-材料3-文件4-增值服务5-其他</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.settlement_type",
            "description": "<p>结算类型1-寄付2-到付</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.settlement_amount",
            "description": "<p>结算金额</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.replace_amount",
            "description": "<p>代收货款</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.status",
            "description": "<p>状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_fullname",
            "description": "<p>收件人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_phone",
            "description": "<p>收件人电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_country",
            "description": "<p>收件人国家</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_country_name",
            "description": "<p>收件人国家名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_post_code",
            "description": "<p>收件人邮编</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_house_number",
            "description": "<p>收件人门牌号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_city",
            "description": "<p>收件人城市</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_street",
            "description": "<p>收件人街道</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_address",
            "description": "<p>收件人详细地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_fullname",
            "description": "<p>发件人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_phone",
            "description": "<p>发件人电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_country",
            "description": "<p>发件人国家</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_country_name",
            "description": "<p>发件人国家名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_province",
            "description": "<p>发件人省份</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_post_code",
            "description": "<p>发件人邮编</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_house_number",
            "description": "<p>发件人门牌号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_city",
            "description": "<p>发件人城市</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_district",
            "description": "<p>发件人区县</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_street",
            "description": "<p>发件人街道</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_address",
            "description": "<p>发件人详细地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.special_remark",
            "description": "<p>特殊事项</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.starting_price",
            "description": "<p>起步价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.transport_price_type",
            "description": "<p>运价方案ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.transport_price_type_name",
            "description": "<p>运价方案名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.receipt_type",
            "description": "<p>回单要求</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.receipt_type_name",
            "description": "<p>回单要求名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.receipt_count",
            "description": "<p>回单数量</p>"
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
            "description": "<p>修改时间</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.package_list",
            "description": "<p>包裹列表</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.id",
            "description": "<p>包裹ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.company_id",
            "description": "<p>公司ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.merchant_id",
            "description": "<p>货主ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.order_no",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.tracking_order_no",
            "description": "<p>运单号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.execution_date",
            "description": "<p>取件日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.second_execution_date",
            "description": "<p>派件日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.expiration_date",
            "description": "<p>有效日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.expiration_status",
            "description": "<p>超期状态1-未超期2-已超期3-超期已处理</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.type",
            "description": "<p>类型1-取2-派</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.name",
            "description": "<p>包裹名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.express_first_no",
            "description": "<p>快递单号1</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.express_second_no",
            "description": "<p>快递单号2</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.feature_logo",
            "description": "<p>特性标志</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.feature",
            "description": "<p>特性</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.out_order_no",
            "description": "<p>外部标识</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.weight",
            "description": "<p>重量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.size",
            "description": "<p>重量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.actual_weight",
            "description": "<p>实际重量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.expect_quantity",
            "description": "<p>预计数量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.actual_quantity",
            "description": "<p>实际数量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.status",
            "description": "<p>状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.stage",
            "description": "<p>阶段1-取件2-中转3-派件</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.sticker_no",
            "description": "<p>贴单号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.settlement_amount",
            "description": "<p>结算金额</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.count_settlement_amount",
            "description": "<p>估算运费</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.sticker_amount",
            "description": "<p>贴单费用</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.delivery_amount",
            "description": "<p>提货费用</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.is_auth",
            "description": "<p>是否需要身份验证1-是2-否</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.auth_fullname",
            "description": "<p>身份人姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.auth_birth_date",
            "description": "<p>身份人出身年月</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.created_at",
            "description": "<p>创建时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.updated_at",
            "description": "<p>修改时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.status_name",
            "description": "<p>状态名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.package_list.type_name",
            "description": "<p>类型名称</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.material_list",
            "description": "<p>材料列表</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.id",
            "description": "<p>材料ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.company_id",
            "description": "<p>公司ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.merchant_id",
            "description": "<p>商户ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.tour_no",
            "description": "<p>取件线路编号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.batch_no",
            "description": "<p>站点编号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.tracking_order_no",
            "description": "<p>运单号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.order_no",
            "description": "<p>订单编号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.execution_date",
            "description": "<p>取派日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.name",
            "description": "<p>材料名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.code",
            "description": "<p>材料代码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.out_order_no",
            "description": "<p>外部标识</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.expect_quantity",
            "description": "<p>预计数量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.actual_quantity",
            "description": "<p>实际数量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.pack_type",
            "description": "<p>包装类型</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.type",
            "description": "<p>类型</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.weight",
            "description": "<p>重量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.size",
            "description": "<p>体积</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.created_at",
            "description": "<p>创建时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.updated_at",
            "description": "<p>修改时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.material_list.type_name",
            "description": "<p>类型名称</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.amount_list",
            "description": "<p>费用列表</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.id",
            "description": "<p>费用ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.company_id",
            "description": "<p>公司ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.order_no",
            "description": "<p>订单号</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.expect_amount",
            "description": "<p>预计金额</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.actual_amount",
            "description": "<p>实际金额</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.type",
            "description": "<p>运费类型</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.in_total",
            "description": "<p>计入总费用1-计入2-不计入</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.status",
            "description": "<p>状态1-预产生2-已产生3-已支付4-已取消</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.created_at",
            "description": "<p>创建时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.updated_at",
            "description": "<p>修改时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.amount_list.type_name",
            "description": "<p>类型名称</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"id\":4206,\"company_id\":3,\"merchant_id\":3,\"merchant_id_name\":\"\\u6b27\\u4e9a\\u5546\\u57ce\",\"order_no\":\"SMAAAEL0001\",\"execution_date\":\"2021-06-10\",\"out_order_no\":\"\",\"create_date\":\"2021-06-09\",\"mask_code\":\"\",\"source\":\"2\",\"source_name\":\"\\u6279\\u91cf\\u5bfc\\u5165\",\"type\":3,\"out_user_id\":\"12036\",\"nature\":1,\"settlement_type\":0,\"settlement_amount\":\"10.00\",\"replace_amount\":\"0.00\",\"status\":1,\"second_place_fullname\":\"EVA\",\"second_place_phone\":\"636985217\",\"second_place_country\":\"\",\"second_place_country_name\":null,\"second_place_post_code\":\"9746TN\",\"second_place_house_number\":\"3-91\",\"second_place_city\":\"\",\"second_place_street\":\"\",\"second_place_address\":\"9746TN 3-91\",\"place_fullname\":\"test\",\"place_phone\":\"123654789\",\"place_country\":\"NL\",\"place_country_name\":\"\\u8377\\u5170\",\"place_province\":\"\",\"place_post_code\":\"1183GT\",\"place_house_number\":\"1\",\"place_city\":\"\",\"place_district\":\"\",\"place_street\":\"\",\"place_address\":\"1 1183GT\",\"special_remark\":\"\",\"remark\":\"\",\"unique_code\":\"\",\"starting_price\":\"10.00\",\"transport_price_type\":\"1\",\"transport_price_type_name\":\"\\u9636\\u68af\\u4e58\\u79ef\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u6bcf\\u5355\\u4f4d\\u91cd\\u91cf\\u4ef7\\u683c*\\u91cd\\u91cf\\u4ef7\\u683c\\uff09*\\uff08\\u6bcf\\u5355\\u4f4d\\u91cc\\u7a0b\\u4ef7\\u683c*\\u91cc\\u7a0b\\u4ef7\\u683c\\uff09\\uff09\",\"receipt_type\":0,\"receipt_type_name\":null,\"receipt_count\":0,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"package_list\":[{\"id\":5080,\"company_id\":3,\"merchant_id\":3,\"order_no\":\"SMAAAEL0001\",\"tracking_order_no\":\"YD00030005577\",\"execution_date\":\"2021-06-10\",\"second_execution_date\":\"2021-06-10\",\"expiration_date\":null,\"expiration_status\":1,\"type\":3,\"name\":\"\",\"express_first_no\":\"10181\",\"express_second_no\":\"\",\"feature_logo\":\"\",\"feature\":1,\"out_order_no\":\"\",\"weight\":\"0.00\",\"size\":1,\"actual_weight\":\"\",\"expect_quantity\":1,\"actual_quantity\":0,\"status\":1,\"stage\":1,\"sticker_no\":\"\",\"settlement_amount\":\"0.00\",\"count_settlement_amount\":\"0.00\",\"sticker_amount\":null,\"delivery_amount\":null,\"remark\":\"\",\"is_auth\":2,\"auth_fullname\":\"\",\"auth_birth_date\":null,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"status_name\":\"\\u672a\\u53d6\\u6d3e\",\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9->\\u914d\\u9001\"}],\"material_list\":[{\"id\":831,\"company_id\":3,\"merchant_id\":3,\"tour_no\":\"\",\"batch_no\":\"\",\"tracking_order_no\":\"YD00030005577\",\"order_no\":\"SMAAAEL0001\",\"execution_date\":\"2021-06-10\",\"name\":\"\",\"code\":\"102\",\"out_order_no\":\"\",\"expect_quantity\":1,\"actual_quantity\":0,\"pack_type\":1,\"type\":1,\"weight\":\"1.00\",\"size\":\"1.00\",\"unit_price\":\"1.00\",\"remark\":\"\",\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u5305\\u88c5\\u6750\\u6599\",\"pack_type_name\":\"\\u7eb8\\u7bb1\"}],\"amount_list\":[{\"id\":2005,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":0,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":null},{\"id\":2006,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":1,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u57fa\\u7840\\u8fd0\\u8d39\"},{\"id\":2007,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":2,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u8d27\\u7269\\u4ef7\\u503c\"},{\"id\":2008,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":3,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u4fdd\\u4ef7\\u8d39\"},{\"id\":2009,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":4,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u5305\\u88c5\\u8d39\"},{\"id\":2010,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":5,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u9001\\u8d27\\u8d39\"},{\"id\":2011,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":6,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u4e0a\\u697c\\u8d39\"},{\"id\":2012,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":7,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u63a5\\u8d27\\u8d39\"},{\"id\":2013,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":8,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u88c5\\u5378\\u8d39\"},{\"id\":2014,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":9,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u5176\\u4ed6\\u8d39\\u7528\"},{\"id\":2015,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":10,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u4ee3\\u6536\\u8d27\\u6b3e\"},{\"id\":2016,\"company_id\":3,\"order_no\":\"SMAAAEL0001\",\"expect_amount\":\"0.00\",\"actual_amount\":\"0.00\",\"type\":11,\"remark\":\"\",\"in_total\":1,\"status\":2,\"created_at\":\"2021-06-10 10:03:51\",\"updated_at\":\"2021-06-10 10:03:51\",\"type_name\":\"\\u8d27\\u6b3e\\u624b\\u7eed\\u8d39\"}]},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/05order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order/{id}"
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
    "url": "/merchant/order/{id}/trail",
    "title": "订单轨迹",
    "name": "订单轨迹",
    "group": "05order",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>订单ID</p>"
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
            "description": "<p>订单ID</p>"
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
            "field": "data.type",
            "description": "<p>类型1-取件2-派件3-取派件，类型为1以发件人为起点取件仓库为终点，类型为2以收件人为终点，派件仓库为起点，类型为3以发件人为起点，收件人为终点，依次经过取件仓库和派件仓库</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_lon",
            "description": "<p>发件人经度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_lat",
            "description": "<p>发件人纬度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_lon",
            "description": "<p>收件人经度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.second_place_lat",
            "description": "<p>收件人纬度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.pickup_warehouse_lon",
            "description": "<p>取件仓库经度 取派件仓库如果重复则只渲染一个</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.pickup_warehouse_lat",
            "description": "<p>派件仓库纬度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.pie_warehouse_lon",
            "description": "<p>派件仓库经度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.pie_warehouse_lat",
            "description": "<p>派件仓库纬度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.trail_list",
            "description": "<p>轨迹列表</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.trail_list.id",
            "description": "<p>轨迹ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.trail_list.content",
            "description": "<p>轨迹内容</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.trail_list.type",
            "description": "<p>轨迹类型5，6为仓库</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.trail_list.created_at",
            "description": "<p>创建时间</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"id\":4224,\"company_id\":3,\"merchant_id\":121,\"merchant_id_name\":\"\\u540c\\u57ce\\u6d3e\\u9001\",\"order_no\":\"SMAAAFC0001\",\"execution_date\":\"2021-06-12\",\"second_execution_date\":\"2021-06-12\",\"out_order_no\":\"BG609NL2077\",\"mask_code\":\"2058\",\"source\":\"3\",\"source_name\":\"\\u7b2c\\u4e09\\u65b9\",\"list_mode\":1,\"transport_mode\":1,\"transport_mode_name\":\"\\u6574\\u8f66\",\"origin_type\":1,\"control_mode\":1,\"control_mode_name\":null,\"origin_type_name\":\"\\u4ece\\u7f51\\u70b9\\u51fa\\u53d1\\uff0c\\u56de\\u5230\\u7f51\\u70b9\",\"type\":3,\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9->\\u914d\\u9001\",\"out_user_id\":\"904566\",\"nature\":1,\"settlement_type\":1,\"settlement_type_name\":\"\\u73b0\\u4ed8\",\"settlement_amount\":\"25.00\",\"replace_amount\":\"0.00\",\"delivery\":2,\"delivery_name\":\"\\u5426\",\"status\":2,\"sticker_amount\":\"0.00\",\"delivery_amount\":\"0.00\",\"status_name\":\"\\u53d6\\u6d3e\\u4e2d\",\"second_place_fullname\":\"Ann\",\"second_place_phone\":\"0031123456789\",\"second_place_country\":\"NL\",\"second_place_country_name\":\"\\u8377\\u5170\",\"second_place_province\":\"Noord-Holland\",\"second_place_post_code\":\"1118CP\",\"second_place_house_number\":\"202\",\"second_place_city\":\"Schiphol\",\"second_place_district\":\"Haarlemmermeer\",\"second_place_street\":\"Evert van de Beekstraat\",\"second_place_address\":\"NL Schiphol Evert van de Beekstraat 202 1118CP\",\"second_place_lon\":\"4.74791023\",\"second_place_lat\":\"52.30389933\",\"place_fullname\":\"Nie\",\"place_phone\":\"0031123654789\",\"place_country\":\"NL\",\"place_country_name\":\"\\u8377\\u5170\",\"place_province\":\"\",\"place_post_code\":\"1055RZ\",\"place_house_number\":\"94\",\"place_city\":\"Amsterdam\",\"place_district\":\"\",\"place_street\":\"Nieuwpoortstraat\",\"place_address\":\"NL Amsterdam Nieuwpoortstraat 94 1055RZ\",\"place_lon\":\"4.85681831\",\"place_lat\":\"52.38420131\",\"special_remark\":null,\"remark\":null,\"starting_price\":\"5.00\",\"transport_price_type\":\"2\",\"transport_price_type_name\":\"\\u9636\\u68af\\u56fa\\u5b9a\\u503c\\u8ba1\\u7b97\\uff08\\u56fa\\u5b9a\\u8d39\\u7528+\\uff08\\u91cd\\u91cf\\u4ef7\\u683c\\u6863\\uff09*\\uff08\\u91cc\\u7a0b\\u4ef7\\u683c\\u6863\\uff09\\uff09\",\"receipt_type\":1,\"receipt_type_name\":\"\\u539f\\u5355\\u8fd4\\u56de\",\"receipt_count\":0,\"create_date\":null,\"created_at\":\"2021-06-11 07:37:54\",\"updated_at\":\"2021-06-11 07:41:50\",\"item_list\":null,\"expect_total_amount\":\"0.00\",\"actual_total_amount\":\"0.00\",\"pickup_warehouse_lon\":\"4.87510019\",\"pickup_warehouse_lat\":\"52.31153083\",\"pie_warehouse_lon\":null,\"pie_warehouse_lat\":null,\"trail_list\":[{\"id\":11331,\"company_id\":3,\"type\":6,\"order_no\":\"SMAAAFC0001\",\"content\":\"\\u8ba2\\u5355[\\u6d3e\\u4ef6]\\u5931\\u8d25\",\"created_at\":\"2021-06-11 07:42:39\"},{\"id\":11330,\"company_id\":3,\"type\":4,\"order_no\":\"SMAAAFC0001\",\"content\":\"\\u8ba2\\u5355[\\u6d3e\\u4ef6]\\u5f00\\u59cb\",\"created_at\":\"2021-06-11 07:41:50\"},{\"id\":11329,\"company_id\":3,\"type\":2,\"order_no\":\"SMAAAFC0001\",\"content\":\"\\u8ba2\\u5355[\\u6d3e\\u4ef6]\\u51c6\\u5907\\u4e2d\\uff0c\\u8d27\\u54c1\\u5df2\\u9501\\u5b9a\",\"created_at\":\"2021-06-11 07:41:35\"},{\"id\":11328,\"company_id\":3,\"type\":7,\"order_no\":\"SMAAAFC0001\",\"content\":\"\\u8ba2\\u5355[\\u6d3e\\u4ef6]\\u8fd0\\u5355\\u521b\\u5efa\\uff0c\\u751f\\u6210\\u8fd0\\u5355\\u53f7[YD00030005603]\\uff0c\\u65e5\\u671f[2021-06-12]\",\"created_at\":\"2021-06-11 07:40:53\"},{\"id\":11327,\"company_id\":3,\"type\":5,\"order_no\":\"SMAAAFC0001\",\"content\":\"\\u8ba2\\u5355[\\u53d6\\u4ef6]\\u5b8c\\u6210\",\"created_at\":\"2021-06-11 07:39:46\"},{\"id\":11326,\"company_id\":3,\"type\":4,\"order_no\":\"SMAAAFC0001\",\"content\":\"\\u8ba2\\u5355[\\u53d6\\u4ef6]\\u5f00\\u59cb\",\"created_at\":\"2021-06-11 07:39:14\"},{\"id\":11325,\"company_id\":3,\"type\":2,\"order_no\":\"SMAAAFC0001\",\"content\":\"\\u8ba2\\u5355[\\u53d6\\u4ef6]\\u51c6\\u5907\\u4e2d\\uff0c\\u8d27\\u54c1\\u5df2\\u9501\\u5b9a\",\"created_at\":\"2021-06-11 07:39:05\"},{\"id\":11324,\"company_id\":3,\"type\":1,\"order_no\":\"SMAAAFC0001\",\"content\":\"\\u8ba2\\u5355\\u521b\\u5efa\\u6210\\u529f\\uff0c\\u8ba2\\u5355\\u53f7[SMAAAFC0001]\\uff0c\\u751f\\u6210\\u8fd0\\u5355\\u53f7[YD00030005602]\",\"created_at\":\"2021-06-11 07:37:54\"}]},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/05order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order/{id}/trail"
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
    "group": "05order",
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
    "filename": "public/api/routes/merchant/05order.php",
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
  },
  {
    "type": "get",
    "url": "/merchant/order/{id}/get-date",
    "title": "通过订单获取可选日期",
    "name": "通过订单获取可选日期",
    "group": "05order",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>订单ID</p>"
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
            "description": "<p>日期数组</p>"
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
    "filename": "public/api/routes/merchant/05order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order/{id}/get-date"
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
    "type": "put",
    "url": "/merchant/order/{id}/assign-batch",
    "title": "重新预约",
    "name": "重新预约",
    "group": "05order",
    "version": "1.0.0",
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
            "field": "execution_date",
            "description": "<p>取派日期</p>"
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
    "filename": "public/api/routes/merchant/05order.php",
    "groupTitle": "订单管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order/{id}/assign-batch"
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
    "type": "post",
    "url": "/merchant/order-import/list",
    "title": "批量新增",
    "name": "批量新增",
    "group": "06orderImport",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list",
            "description": "<p>多个订单数据</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.create_date",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.type",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.merchant",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.out_user_id",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.out_order_no",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_fullname",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_phone",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_post_code",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_house_number",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_city",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_street",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.execution_date",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_fullname",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_phone",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_post_code",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_house_number",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_city",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_street",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_execution_date",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_6",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_7",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_8",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_9",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_10",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_11",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.settlement_amount",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.settlement_type",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.control_mode",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.receipt_type",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.receipt_count",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.special_remark",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.mask_code",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_no_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_name_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_weight_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_feature_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_remark_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_expiration_date_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_out_order_no_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_no_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_name_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_weight_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_feature_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_remark_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_expiration_date_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_out_order_no_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_no_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_name_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_weight_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_feature_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_remark_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_expiration_date_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_out_order_no_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_no_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_name_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_weight_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_feature_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_remark_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_expiration_date_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_out_order_no_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_no_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_name_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_weight_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_feature_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_remark_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_expiration_date_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_out_order_no_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_code_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_name_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_count_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_weight_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_size_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_type_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_pack_type_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_price_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_remark_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_out_order_no_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_code_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_name_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_count_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_weight_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_size_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_type_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_pack_type_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_price_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_remark_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_out_order_no_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_code_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_name_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_count_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_weight_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_size_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_type_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_pack_type_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_price_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_remark_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_out_order_no_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_code_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_name_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_count_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_weight_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_size_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_type_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_pack_type_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_price_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_remark_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_out_order_no_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_code_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_name_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_count_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_weight_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_size_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_type_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_pack_type_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_price_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_remark_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_out_order_no_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.merchant_id",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_country",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_country",
            "description": ""
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
          "content": "{\"code\":200,\"data\":\"\",\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/06orderImport.php",
    "groupTitle": "订单导入",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order-import/list"
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
    "url": "/merchant/order-import/check",
    "title": "检查",
    "name": "检查",
    "group": "06orderImport",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list",
            "description": "<p>多个订单数据</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.create_date",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.type",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.merchant",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.out_user_id",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.out_order_no",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_fullname",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_phone",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_post_code",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_house_number",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_city",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_street",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.execution_date",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_fullname",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_phone",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_post_code",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_house_number",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_city",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_street",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_execution_date",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_6",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_7",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_8",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_9",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_10",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.amount_11",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.settlement_amount",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.settlement_type",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.control_mode",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.receipt_type",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.receipt_count",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.special_remark",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.mask_code",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_no_1:",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_name_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_weight_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_feature_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_remark_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_expiration_date_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_out_order_no_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_no_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_name_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_weight_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_feature_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_remark_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_expiration_date_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_out_order_no_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_no_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_name_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_weight_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_feature_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_remark_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_expiration_date_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_out_order_no_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_no_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_name_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_weight_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_feature_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_remark_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_expiration_date_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_out_order_no_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_no_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_name_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_weight_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_feature_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_remark_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_expiration_date_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.package_out_order_no_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_code_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_name_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_count_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_weight_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_size_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_type_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_pack_type_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_price_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_remark_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_out_order_no_1",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_code_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_name_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_count_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_weight_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_size_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_type_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_pack_type_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_price_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_remark_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_out_order_no_2",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_code_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_name_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_count_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_weight_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_size_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_type_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_pack_type_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_price_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_remark_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_out_order_no_3",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_code_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_name_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_count_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_weight_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_size_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_type_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_pack_type_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_price_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_remark_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_out_order_no_4",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_code_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_name_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_count_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_weight_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_size_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_type_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_pack_type_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_price_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_remark_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.material_out_order_no_5",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.merchant_id",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.place_country",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "list.second_place_country",
            "description": ""
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
            "field": "data.status",
            "description": "<p>状态1-通过2-不通过</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.error",
            "description": "<p>错误，有错误才会有数据，没有错误返回空数组。</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.error.log",
            "description": "<p>总体错误</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.error.field",
            "description": "<p>字段错误，field代包含所有请求参数字段</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.place_lat",
            "description": "<p>发件人经度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.place_lon",
            "description": "<p>发件人纬度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.second_place_lat",
            "description": "<p>收件人经度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.second_place_lon",
            "description": "<p>收件人纬度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.count_settlement_amount",
            "description": "<p>预计运价</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":[{\"status\":2,\"error\":{\"out_order_no\":\"\\u8d27\\u53f7 \\u5df2\\u5b58\\u5728\"},\"data\":{\"create_date\":\"2021-06-11\",\"type\":\"3\",\"merchant\":\"\\u540c\\u57ce\\u6d3e\\u9001\",\"out_user_id\":\"\",\"out_order_no\":\"01236\",\"place_fullname\":\"test\",\"place_phone\":\"123654791\",\"place_post_code\":\"1183GT\",\"place_house_number\":1,\"place_city\":\"Amstelveen\",\"place_street\":\"Straat van Gibraltar\",\"execution_date\":\"2021-06-11\",\"second_place_fullname\":\"ANN\",\"second_place_phone\":\"636985219\",\"second_place_post_code\":\"1183GT\",\"second_place_house_number\":11,\"second_place_city\":\"Amstelveen\",\"second_place_street\":\"Straat van Gibraltar\",\"second_execution_date\":\"2021-06-11\",\"amount_1\":\"\",\"amount_2\":\"\",\"amount_3\":\"\",\"amount_4\":\"\",\"amount_5\":\"\",\"amount_6\":\"\",\"amount_7\":\"\",\"amount_8\":\"\",\"amount_9\":\"\",\"amount_10\":\"\",\"amount_11\":\"\",\"settlement_amount\":\"5.00\",\"settlement_type\":\"\",\"control_mode\":\"\",\"receipt_type\":\"\",\"receipt_count\":\"\",\"special_remark\":\"\",\"mask_code\":\"\",\"package_no_1\":\"10171\",\"package_name_1\":\"\",\"package_weight_1\":\"\",\"package_feature_1\":\"\",\"package_remark_1\":\"\",\"package_expiration_date_1\":\"\",\"package_out_order_no_1\":\"\",\"package_no_2\":\"\",\"package_name_2\":\"\",\"package_weight_2\":\"\",\"package_feature_2\":\"\",\"package_remark_2\":\"\",\"package_expiration_date_2\":\"\",\"package_out_order_no_2\":\"\",\"package_no_3\":\"\",\"package_name_3\":\"\",\"package_weight_3\":\"\",\"package_feature_3\":\"\",\"package_remark_3\":\"\",\"package_expiration_date_3\":\"\",\"package_out_order_no_3\":\"\",\"package_no_4\":\"\",\"package_name_4\":\"\",\"package_weight_4\":\"\",\"package_feature_4\":\"\",\"package_remark_4\":\"\",\"package_expiration_date_4\":\"\",\"package_out_order_no_4\":\"\",\"package_no_5\":\"\",\"package_name_5\":\"\",\"package_weight_5\":\"\",\"package_feature_5\":\"\",\"package_remark_5\":\"\",\"package_expiration_date_5\":\"\",\"package_out_order_no_5\":\"\",\"material_code_1\":\"\",\"material_name_1\":\"\",\"material_count_1\":\"\",\"material_weight_1\":\"\",\"material_size_1\":\"\",\"material_type_1\":\"\",\"material_pack_type_1\":\"\",\"material_price_1\":\"\",\"material_remark_1\":\"\",\"material_out_order_no_1\":\"\",\"material_code_2\":\"\",\"material_name_2\":\"\",\"material_count_2\":\"\",\"material_weight_2\":\"\",\"material_size_2\":\"\",\"material_type_2\":\"\",\"material_pack_type_2\":\"\",\"material_price_2\":\"\",\"material_remark_2\":\"\",\"material_out_order_no_2\":\"\",\"material_code_3\":\"\",\"material_name_3\":\"\",\"material_count_3\":\"\",\"material_weight_3\":\"\",\"material_size_3\":\"\",\"material_type_3\":\"\",\"material_pack_type_3\":\"\",\"material_price_3\":\"\",\"material_remark_3\":\"\",\"material_out_order_no_3\":\"\",\"material_code_4\":\"\",\"material_name_4\":\"\",\"material_count_4\":\"\",\"material_weight_4\":\"\",\"material_size_4\":\"\",\"material_type_4\":\"\",\"material_pack_type_4\":\"\",\"material_price_4\":\"\",\"material_remark_4\":\"\",\"material_out_order_no_4\":\"\",\"material_code_5\":\"\",\"material_name_5\":\"\",\"material_count_5\":\"\",\"material_weight_5\":\"\",\"material_size_5\":\"\",\"material_type_5\":\"\",\"material_pack_type_5\":\"\",\"material_price_5\":\"\",\"material_remark_5\":\"\",\"material_out_order_no_5\":\"\",\"merchant_id\":\"121\",\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9->\\u914d\\u9001\",\"place_country\":\"NL\",\"second_place_country\":\"NL\",\"place_address\":\"NL Amstelveen Straat van Gibraltar 1 1183GT\",\"second_place_address\":\"NL Groningen Groningen 11 1183GT\",\"place_province\":\"Noord-Holland\",\"place_district\":\"Amstelveen\",\"place_lat\":52.31153637,\"place_lon\":4.87465697,\"second_place_province\":\"Noord-Holland\",\"second_place_district\":\"Amstelveen\",\"second_place_lat\":52.31153083,\"second_place_lon\":4.87510019,\"distance\":1,\"count_settlement_amount\":\"5.00\",\"package_settlement_amount\":\"0.00\",\"starting_price\":\"5.00\",\"transport_price_id\":71,\"transport_price_type\":2}}],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/06orderImport.php",
    "groupTitle": "订单导入",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order-import/check"
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
    "url": "/merchant/order-import",
    "title": "订单导入",
    "name": "订单导入",
    "group": "06orderImport",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "file",
            "optional": false,
            "field": "file",
            "description": "<p>表格文件</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Param-Response:",
          "content": "{\"file\":\"1.xlsx\"}",
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
          "content": "{\"code\":200,\"data\":[{\"create_date\":\"2021-06-10\",\"type\":\"1\",\"merchant\":\"\\u6b27\\u4e9a\\u5546\\u57ce\",\"out_user_id\":\"\",\"out_order_no\":\"\",\"place_fullname\":\"123123\",\"place_phone\":\"123123\",\"place_post_code\":\"2153PJ\",\"place_house_number\":\"20\",\"place_city\":\"\",\"place_street\":\"\",\"execution_date\":\"2021-06-10\",\"second_place_fullname\":\"\",\"second_place_phone\":\"\",\"second_place_post_code\":\"\",\"second_place_house_number\":\"\",\"second_place_city\":\"\",\"second_place_street\":\"\",\"second_execution_date\":\"\",\"amount_1\":\"\",\"amount_2\":\"\",\"amount_3\":\"\",\"amount_4\":\"\",\"amount_5\":\"\",\"amount_6\":\"\",\"amount_7\":\"\",\"amount_8\":\"\",\"amount_9\":\"\",\"amount_10\":\"\",\"amount_11\":\"\",\"settlement_amount\":\"\",\"settlement_type\":\"\",\"control_mode\":\"\",\"receipt_type\":\"\",\"receipt_count\":\"\",\"special_remark\":\"\",\"mask_code\":\"\",\"package_no_1\":\"\",\"package_name_1\":\"\",\"package_weight_1\":\"\",\"package_feature_1\":\"\",\"package_remark_1\":\"\",\"package_expiration_date_1\":\"\",\"package_out_order_no_1\":\"\",\"package_no_2\":\"\",\"package_name_2\":\"\",\"package_weight_2\":\"\",\"package_feature_2\":\"\",\"package_remark_2\":\"\",\"package_expiration_date_2\":\"\",\"package_out_order_no_2\":\"\",\"package_no_3\":\"\",\"package_name_3\":\"\",\"package_weight_3\":\"\",\"package_feature_3\":\"\",\"package_remark_3\":\"\",\"package_expiration_date_3\":\"\",\"package_out_order_no_3\":\"\",\"package_no_4\":\"\",\"package_name_4\":\"\",\"package_weight_4\":\"\",\"package_feature_4\":\"\",\"package_remark_4\":\"\",\"package_expiration_date_4\":\"\",\"package_out_order_no_4\":\"\",\"package_no_5\":\"\",\"package_name_5\":\"\",\"package_weight_5\":\"\",\"package_feature_5\":\"\",\"package_remark_5\":\"\",\"package_expiration_date_5\":\"\",\"package_out_order_no_5\":\"\",\"material_code_1\":\"\",\"material_name_1\":\"\",\"material_count_1\":\"\",\"material_weight_1\":\"\",\"material_size_1\":\"\",\"material_type_1\":\"\",\"material_pack_type_1\":\"\",\"material_price_1\":\"\",\"material_remark_1\":\"\",\"material_out_order_no_1\":\"\",\"material_code_2\":\"\",\"material_name_2\":\"\",\"material_count_2\":\"\",\"material_weight_2\":\"\",\"material_size_2\":\"\",\"material_type_2\":\"\",\"material_pack_type_2\":\"\",\"material_price_2\":\"\",\"material_remark_2\":\"\",\"material_out_order_no_2\":\"\",\"material_code_3\":\"\",\"material_name_3\":\"\",\"material_count_3\":\"\",\"material_weight_3\":\"\",\"material_size_3\":\"\",\"material_type_3\":\"\",\"material_pack_type_3\":\"\",\"material_price_3\":\"\",\"material_remark_3\":\"\",\"material_out_order_no_3\":\"\",\"material_code_4\":\"\",\"material_name_4\":\"\",\"material_count_4\":\"\",\"material_weight_4\":\"\",\"material_size_4\":\"\",\"material_type_4\":\"\",\"material_pack_type_4\":\"\",\"material_price_4\":\"\",\"material_remark_4\":\"\",\"material_out_order_no_4\":\"\",\"material_code_5\":\"\",\"material_name_5\":\"\",\"material_count_5\":\"\",\"material_weight_5\":\"\",\"material_size_5\":\"\",\"material_type_5\":\"\",\"material_pack_type_5\":\"\",\"material_price_5\":\"\",\"material_remark_5\":\"\",\"material_out_order_no_5\":\"\",\"merchant_id\":\"3\",\"type_name\":\"\\u63d0\\u8d27->\\u7f51\\u70b9\",\"place_country\":\"NL\"}],\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/06orderImport.php",
    "groupTitle": "订单导入",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order-import"
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
    "url": "/merchant/order-import/template",
    "title": "订单导入模板",
    "name": "订单导入模板",
    "group": "06orderImport",
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
            "description": "<p>下载链接</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"name\":\"66f6181bcb4cff4cd38fbc804a036db6.xlsx\",\"path\":\"tms-api.test\\/storage\\/admin\\/excel\\/3\\/order\\/66f6181bcb4cff4cd38fbc804a036db6.xlsx\"},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/06orderImport.php",
    "groupTitle": "订单导入",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/order-import/template"
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
    "type": "put",
    "url": "/merchant/address/{id}",
    "title": "地址修改",
    "name": "地址修改",
    "group": "08address",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>地址ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_fullname",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_phone",
            "description": "<p>电话</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型1-收件人2-发件人</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type_name",
            "description": "<p>类型名称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_address",
            "description": "<p>详细地址</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_country",
            "description": "<p>国家</p>"
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
            "field": "place_post_code",
            "description": "<p>邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_house_number",
            "description": "<p>门牌号</p>"
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
            "description": "<p>区域</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_street",
            "description": "<p>街道</p>"
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
    "filename": "public/api/routes/merchant/08address.php",
    "groupTitle": "地址管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/address/{id}"
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
    "type": "delete",
    "url": "/merchant/address/{id}",
    "title": "地址删除",
    "name": "地址删除",
    "group": "08address",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>地址ID</p>"
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
    "filename": "public/api/routes/merchant/08address.php",
    "groupTitle": "地址管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/address/{id}"
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
    "type": "post",
    "url": "/merchant/address/{id}",
    "title": "地址新增",
    "name": "地址新增",
    "group": "08address",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_fullname",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_phone",
            "description": "<p>电话</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>类型1-收件人2-发件人</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type_name",
            "description": "<p>类型名称</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_address",
            "description": "<p>详细地址</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_country",
            "description": "<p>国家</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_country_name",
            "description": "<p>国家名称</p>"
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
            "field": "place_post_code",
            "description": "<p>邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_house_number",
            "description": "<p>门牌号</p>"
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
            "description": "<p>区域</p>"
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
            "field": "place_lon",
            "description": "<p>经度</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_lat",
            "description": "<p>纬度</p>"
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
    "filename": "public/api/routes/merchant/08address.php",
    "groupTitle": "地址管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/address/{id}"
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
    "url": "/merchant/address",
    "title": "地址查询",
    "name": "地址查询",
    "group": "08address",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
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
            "field": "place_post_code",
            "description": "<p>邮编</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_phone",
            "description": "<p>电话</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "place_fullname",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "per_page",
            "description": "<p>每页显示条数</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "page",
            "description": "<p>页码</p>"
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
            "field": "data.data",
            "description": "<p>返回数据</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.id",
            "description": "<p>地址ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.place_fullname",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.place_phone",
            "description": "<p>电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.type",
            "description": "<p>类型1-收件人2-发件人</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.type_name",
            "description": "<p>类型名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.place_address",
            "description": "<p>详细地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.created_at",
            "description": "<p>创建日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.data.updated_at",
            "description": "<p>修改日期</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.links",
            "description": "<p>跳转信息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.links.first",
            "description": "<p>第一页</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.links.last",
            "description": "<p>最后一页</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.links.prev",
            "description": "<p>前一页</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.links.next",
            "description": "<p>后一页</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.meta",
            "description": "<p>页码信息</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.current_page",
            "description": "<p>当前页码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.from",
            "description": "<p>起始条数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.last_page",
            "description": "<p>末页页码</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.path",
            "description": "<p>地址</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.per_page",
            "description": "<p>每页显示条数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.to",
            "description": "<p>终止条数</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.meta.total",
            "description": "<p>总条数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"data\":[{\"id\":3,\"place_fullname\":\"wangwenxuan\",\"place_phone\":\"0031612345678\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Amstelveen Straat van Gibraltar 11 1183GT\",\"created_at\":\"2020-07-23 13:42:38\",\"updated_at\":\"2020-07-23 13:42:38\"},{\"id\":4,\"place_fullname\":\"wwx\",\"place_phone\":\"0031612345678\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Nieuw-Vennep Pesetaweg 20 2153PJ\",\"created_at\":\"2020-07-23 13:47:59\",\"updated_at\":\"2020-07-23 13:47:59\"},{\"id\":5,\"place_fullname\":\"wwx\",\"place_phone\":\"0031321231231\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Amstelveen Kierkegaardstraat 7 1185AH\",\"created_at\":\"2020-07-23 13:52:54\",\"updated_at\":\"2020-07-23 13:52:54\"},{\"id\":8,\"place_fullname\":\"xiaoxao\",\"place_phone\":\"0031329109023\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Amstelveen Straat van Gibraltar 7 1183GT\",\"created_at\":\"2020-07-23 14:11:40\",\"updated_at\":\"2020-07-23 14:11:40\"},{\"id\":16,\"place_fullname\":\"yefen\",\"place_phone\":\"0031612345678\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Amstelveen Straat van Gibraltar 11 1183GT\",\"created_at\":\"2020-07-23 15:56:28\",\"updated_at\":\"2020-07-23 15:56:28\"},{\"id\":17,\"place_fullname\":\"ada\",\"place_phone\":\"0031123456789\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Schiphol Evert van de Beekstraat 202 1118 CP\",\"created_at\":\"2020-07-23 15:56:57\",\"updated_at\":\"2020-07-23 15:56:57\"},{\"id\":18,\"place_fullname\":\"CS\",\"place_phone\":\"0031912365487\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Amstelveen Meerpaal 11 1186ZM\",\"created_at\":\"2020-07-23 15:58:03\",\"updated_at\":\"2020-07-23 15:58:03\"},{\"id\":19,\"place_fullname\":\"DD\",\"place_phone\":\"0031632145678\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Amstelveen Tamarindelaan 29 1187EM\",\"created_at\":\"2020-07-23 15:58:52\",\"updated_at\":\"2020-07-23 15:58:52\"},{\"id\":20,\"place_fullname\":\"Cui\",\"place_phone\":\"0031698754321\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Amstelveen Terschellingstraat 14 1181HK\",\"created_at\":\"2020-07-23 15:59:33\",\"updated_at\":\"2020-07-23 15:59:33\"},{\"id\":21,\"place_fullname\":\"Ak\",\"place_phone\":\"0031623541789\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Amstelveen Birkhoven 49 1187JW\",\"created_at\":\"2020-07-23 16:00:00\",\"updated_at\":\"2020-07-23 16:00:00\"}],\"links\":{\"first\":\"http:\\/\\/tms-api.test:10002\\/api\\/merchant\\/address?page=1\",\"last\":\"http:\\/\\/tms-api.test:10002\\/api\\/merchant\\/address?page=48\",\"prev\":null,\"next\":\"http:\\/\\/tms-api.test:10002\\/api\\/merchant\\/address?page=2\"},\"meta\":{\"current_page\":1,\"from\":1,\"last_page\":48,\"path\":\"http:\\/\\/tms-api.test:10002\\/api\\/merchant\\/address\",\"per_page\":\"10\",\"to\":10,\"total\":474}},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/08address.php",
    "groupTitle": "地址管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/address"
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
    "url": "/merchant/address/{id}",
    "title": "地址详情",
    "name": "地址详情",
    "group": "08address",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>地址ID</p>"
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
            "description": "<p>地址ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_fullname",
            "description": "<p>姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_phone",
            "description": "<p>电话</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.type",
            "description": "<p>类型1-收件人2-发件人</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.type_name",
            "description": "<p>类型名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_address",
            "description": "<p>详细地址</p>"
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
            "field": "data.place_country_name",
            "description": "<p>国家名称</p>"
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
            "field": "data.place_post_code",
            "description": "<p>邮编</p>"
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
            "field": "data.place_city",
            "description": "<p>城市</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_district",
            "description": "<p>区域</p>"
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
            "field": "data.place_lon",
            "description": "<p>经度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.place_lat",
            "description": "<p>纬度</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.created_at",
            "description": "<p>创建日期</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.updated_at",
            "description": "<p>修改日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"id\":3,\"place_fullname\":\"wangwenxuan\",\"place_phone\":\"0031612345678\",\"type\":1,\"type_name\":\"\\u53d1\\u4ef6\\u4eba\",\"place_address\":\"NL Amstelveen Straat van Gibraltar 11 1183GT\",\"place_country\":\"NL\",\"place_country_name\":\"\\u8377\\u5170\",\"place_province\":\"\",\"place_post_code\":\"1183GT\",\"place_house_number\":\"11\",\"place_city\":\"Amstelveen\",\"place_district\":\"\",\"place_street\":\"Straat van Gibraltar\",\"place_lon\":\"4.87510019\",\"place_lat\":\"52.31153083\",\"created_at\":\"2020-07-23 13:42:38\",\"updated_at\":\"2020-07-23 13:42:38\"},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/08address.php",
    "groupTitle": "地址管理",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/address/{id}"
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
    "url": "/merchant/transport-price",
    "title": "运价查询",
    "name": "运价查询",
    "group": "09price",
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
            "type": "String",
            "optional": false,
            "field": "data.id",
            "description": "<p>id 运价方案ID</p>"
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
            "field": "data.name",
            "description": "<p>名称</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.starting_price",
            "description": "<p>起步价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.type",
            "description": "<p>类型1-阶梯乘积2-阶梯固定</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.remark",
            "description": "<p>特别说明</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.status",
            "description": "<p>状态1-启用2-禁用</p>"
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
            "description": "<p>修改时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.type_name",
            "description": "<p>类型名称</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.weight_list",
            "description": "<p>重量费用列表</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.weight_list.id",
            "description": "<p>重量费用ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.weight_list.company_id",
            "description": "<p>公司ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.weight_list.transport_price_id",
            "description": "<p>运价ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.weight_list.start",
            "description": "<p>运价ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.weight_list.end",
            "description": "<p>截止重量</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.weight_list.price",
            "description": "<p>加价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.weight_list.created_at",
            "description": "<p>创建时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.weight_list.updated_at",
            "description": "<p>修改时间</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.km_list",
            "description": "<p>里程费用列表</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.km_list.id",
            "description": "<p>里程费用ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.km_list.company_id",
            "description": "<p>公司ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.km_list.transport_price_id",
            "description": "<p>运价ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.km_list.start",
            "description": "<p>起始公里</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.km_list.end",
            "description": "<p>截止公里</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.km_list.price",
            "description": "<p>加价</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.km_list.created_at",
            "description": "<p>创建时间</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.km_list.updated_at",
            "description": "<p>修改时间</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\"code\":200,\"data\":{\"id\":67,\"company_id\":3,\"name\":\"测试名称3\",\"starting_price\":\"10.00\",\"type\":2,\"remark\":\"\",\"status\":1,\"created_at\":\"2021-02-03 08:13:54\",\"updated_at\":\"2021-02-03 08:13:54\",\"type_name\":\"阶梯固定值计算（固定费用+（重量价格档）*（里程价格档））\",\"km_list\":[{\"id\":157,\"company_id\":3,\"transport_price_id\":67,\"start\":0,\"end\":100,\"price\":\"0.00\",\"created_at\":\"2021-02-03 08:13:54\",\"updated_at\":\"2021-02-03 08:13:54\"},{\"id\":158,\"company_id\":3,\"transport_price_id\":67,\"start\":100,\"end\":999999999,\"price\":\"10.00\",\"created_at\":\"2021-02-03 08:13:54\",\"updated_at\":\"2021-02-03 08:13:54\"}],\"weight_list\":[{\"id\":180,\"company_id\":3,\"transport_price_id\":69,\"start\":0,\"end\":5,\"price\":\"2.00\",\"created_at\":\"2021-02-04 04:38:06\",\"updated_at\":\"2021-02-04 04:38:06\"},{\"id\":181,\"company_id\":3,\"transport_price_id\":69,\"start\":5,\"end\":999999999,\"price\":\"5.00\",\"created_at\":\"2021-02-04 04:38:06\",\"updated_at\":\"2021-02-04 04:38:06\"}],\"special_time_list\":[]},\"msg\":\"successful\"}",
          "type": "json"
        }
      ]
    },
    "filename": "public/api/routes/merchant/09price.php",
    "groupTitle": "运价信息",
    "sampleRequest": [
      {
        "url": "https://dev-tms.nle-tech.com/api/merchant/transport-price"
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
