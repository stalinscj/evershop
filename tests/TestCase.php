<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Assert that the session has the give error key value.
     *
     * @param string $key
     * @param string $value
     * @param int    $index
     * 
     * @return void
     */
    protected function assertSessionHasErrorKeyValue($key, $value, $index = 0)
    {
        $errors = session('errors');

        $error = optional($errors)->get($key)[$index] ?? '';

        $this->assertEquals($value, $error, "Session missing error: '$key' with '$value' at index $index");

        return $this;
    }
}
