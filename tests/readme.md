## 安装 phpunit

### 项目安装
```bash
composer require --dev phpunit/phpunit
```
使用 `./vendor/bin/phpunit`

### 全局安装
```bash
composer global require --dev phpunit/phpunit
```
使用 `phpunit`

## 快速入门

### 基本格式

所有测试类都需要继承 `Tests\TestCase`

- 测试类命名： `类名 + Test` ， eg `FooClassTest`
- 测试方法命名： `test + 方法名`, eg `testFoo`

> 也可以使用注释 `@test` 来标注需要测试的方法

### 测试用例
 - 测试所有单元: `phpunit` or `./vendor/bin/phpunit`
 - 测试指定单元: `phpunit Tests\Api\Admin\WarehouseTest`
 - 测试指定方法: `phpunit --filter "/(Tests\\Api\\Admin\\WarehouseTest::testTree)( .*)?$/"`

### 环境变量
```shell
cp .env.testing.example .env.testing
```

### 官方文档

[https://phpunit.readthedocs.io/zh_CN/latest/index.html](https://phpunit.readthedocs.io/zh_CN/latest/index.html)
