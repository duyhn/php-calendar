<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTest;
use \Mockery;
use Dotenv\Dotenv;

abstract class TestCase extends BaseTest
{

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $dotenv = new Dotenv(__DIR__);
        $dotenv->load();
    }
}