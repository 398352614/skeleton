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
}
