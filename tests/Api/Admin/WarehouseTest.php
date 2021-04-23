<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/21/2021
 * Time : 5:39 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: WarehouseTest.php
 */


namespace Tests\Api\Admin;


/**
 * Class WarehouseTest
 * @package Tests\Api\Admin
 */
class WarehouseTest extends BaseTest
{
    /**
     * 列表查询
     */
    public function testIndex()
    {
        $response = $this->getJson('/api/admin/warehouse', [
            'Authorization' => $this->token
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'msg' => 'successful']);
    }

    /**
     * 新增
     */
    public function testStore()
    {
        $response = $this->postJson('/api/admin/warehouse', [
            'name' => '长沙芙蓉仓' . rand(999, 9999),
            'fullname' => '胡洋铭',
            'phone' => '17570715315',
            'country' => 'CN',
            'post_code' => '430102',
            'house_number' => '26号',
            'city' => '长沙市',
            'street' => '尚德街',
            'address' => '湖南省长沙市芙蓉区尚德街26号',
            'lon' => '112.979478383835',
            'lat' => '28.192292827154844',
            'type' => '1',
            'is_center' => '1',
            'acceptance_type' => '1',
            'company_name' => 'NLE',
            'email' => 'nzl@q.com',
            'parent' => '4',
            'avatar' => 'url',
        ], [
            'Authorization' => $this->token
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'msg' => 'successful']);

        return json_decode($response->getContent(), true)['data']['id'];
    }

    /**
     * 获取详情
     * @depends testStore
     */
    public function testDetail(int $id)
    {
        $response = $this->getJson('/api/admin/warehouse/' . $id, [
            'Authorization' => $this->token
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'msg' => 'successful']);

        return json_decode($response->getContent(), true)['data'];
    }

    /**
     * 更新
     * @depends testDetail
     */
    public function testUpdate(array $data)
    {
        $response = $this->putJson('/api/admin/warehouse/' . $data['id'], $data, [
            'Authorization' => $this->token
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'msg' => 'successful']);

        return $data['id'];
    }

    /**
     * 删除
     * @depends testUpdate
     */
    public function testDelete(int $id)
    {
        $response = $this->deleteJson('/api/admin/warehouse/' . $id, [], [
            'Authorization' => $this->token
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'msg' => 'successful']);
    }

    /**
     * 测试网点节点树
     */
    public function testTree()
    {
        $response = $this->get('/api/admin/warehouse/tree', [
            'Authorization' => $this->token
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'msg' => 'successful']);
    }

    /**
     * 移动节点
     */
    public function testMoveNode()
    {
        $response = $this->put('/api/admin/warehouse/15/move/11');

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data', 'msg']);
        $response->assertJsonFragment(['code' => 200, 'data' => '', 'msg' => 'successful']);
    }
}
