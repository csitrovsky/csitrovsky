<?php

namespace test;

use app\src\Database;
use Exception;

class Request extends Database
{

    /**
     * @return false
     */
    public function engine(): ?bool
    {
        try {
            return false;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}