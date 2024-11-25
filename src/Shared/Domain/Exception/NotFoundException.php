<?php

namespace App\Shared\Domain\Exception;

class NotFoundException extends \Exception
{
    public function __construct($message = "Not found!", $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}