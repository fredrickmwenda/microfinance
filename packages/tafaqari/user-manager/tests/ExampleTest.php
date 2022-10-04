<?php

namespace Tafaqari\UserManager\Tests;

use Orchestra\Testbench\TestCase;
use Tafaqari\UserManager\UserManagerServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [UserManagerServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
