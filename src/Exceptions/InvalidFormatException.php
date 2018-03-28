<?php

namespace J0sh0nat0r\SimpleConfig\Exceptions;

class InvalidFormatException extends \Exception
{
    public function __construct($format)
    {
        parent::__construct("The format `$format` is invalid");
    }
}
