<?php

namespace Alihoushy\FilamentPersianSuite\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            \Alihoushy\FilamentPersianSuite\FilamentPersianSuiteServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        // Setup environment
    }
}

