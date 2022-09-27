<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    private Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    /**
     * @param $key
     *
     * @return Generator
     */
    public function __get($key)
    {
        if ($key === 'faker') {
            return $this->faker;
        }
        throw new \RuntimeException('Unknown Key Requested');
    }
}
