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


use Tests\TestCase;

/**
 * Class WarehouseTest
 * @package Tests\Api\Admin
 */
class WarehouseTest extends TestCase
{
    /**
     * 测试网点节点树
     */
    public function testTree()
    {
        $response = $this->get('/api/admin/warehouse/tree', [
            'Authorization' => $this->token
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'data']);
    }
}
