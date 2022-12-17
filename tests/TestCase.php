<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    protected function setUp():void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    protected function tearDown():void
    {
        DB::rollback();
        parent::tearDown();
    }
}
