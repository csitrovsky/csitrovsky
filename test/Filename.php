<?php

namespace test;

use app\src\Error;
use JsonException;

class Filename
{

    /**
     * @return void
     * @throws JsonException
     */
    public function engine(): void
    {
        (new Error())->throw('The autoloader is working properly...');
    }
}