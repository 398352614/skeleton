<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class TestCase
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @var mixed|string
     */
    protected $token = '';

    use CreatesApplication;

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

        $data = json_decode($response->getContent(), true);

        $this->token = 'Bearer ' . $data['data']['access_token'];
    }
}
