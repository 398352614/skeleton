<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/22/2021
 * Time : 3:33 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: BaseTest.php
 */


namespace Tests\Api\Admin;


use Tests\TestCase;

/**
 * Class BaseTest
 * @package Tests\Api\Admin
 */
class BaseTest extends TestCase
{
    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $response = $this->postJson('/api/admin/login', [
            'username' => env('USER_NAME'),
            'password' => env('PASS_WORD')
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'msg' => 'successful']);

        $data = json_decode($response->getContent(), true);

        $this->token = 'Bearer ' . $data['data']['access_token'];
    }
}
