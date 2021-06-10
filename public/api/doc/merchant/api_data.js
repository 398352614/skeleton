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
    "url": "/merchant/statistics/this-week",
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
    "url": "/merchant/statistics/this-week",
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
    "url": "/merchant",
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
    "url": "/merchant/{id}",
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
        "url": "https://dev-tms.nle-tech.com/api/merchant/{id}"
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
