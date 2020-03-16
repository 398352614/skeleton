<?php

namespace Tests\Unit;

use App\Services\CurlClient;
use PHPUnit\Framework\TestCase;

class GuzzleTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testPostJson()
    {
        $url = 'https://dev-distancematrix.nle-tech.com/api/update-line?api_key=nletech&timestamp=1584347717&sign=a02822e91d08baba93f08e8cb63ae560a226e6b98c5a75b56d125a23d18c9e0e';

        $curl = new CurlClient();

        $res = $curl->postJson($url, []);

        dd($res);
    }
}
