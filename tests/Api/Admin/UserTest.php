<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/22/2021
 * Time : 2:40 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: UserTest.php
 */


namespace Tests\Api\Admin;


/**
 * Class UserTest
 * @package Tests\Api\Admin
 */
class UserTest extends BaseTest
{
    /**
     * 注册
     */
    public function testRegister()
    {
        $response = $this->post('/api/admin/register', [
            'email' => 'nzl@qq.com',
            'password' => '123456..',
            'confirm_password'  => '123456..',
            'name' => 'NiZerin',
            'code' => ''
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'data' => '', 'msg' => 'successful']);
    }

    /**
     * 找回密码
     */
    public function testPasswordReset()
    {
        $response = $this->put('/api/admin/password-reset', [
            'email' => 'nzl@qq.com',
            'code' => '',
            'new_password' => '12345678',
            'confirm_new_password' => '12345678'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'data' => '', 'msg' => 'successful']);
    }

    /**
     * 修改密码
     */
    public function testChangePassword()
    {
        $response = $this->put('/api/admin/my-password', [
            'origin_password' => '123456..',
            'new_password' => '12345678',
            'confirm_new_password' => '12345678'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'data' => '', 'msg' => 'successful']);
    }
}
