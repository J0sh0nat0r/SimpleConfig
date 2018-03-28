<?php

namespace J0sh0nat0r\SimpleConfig\Exceptions;

class ConfigNotFoundException extends \Exception
{
    public function __construct($file)
    {
        parent::__construct("Failed to find config file: $file");
    }
}
