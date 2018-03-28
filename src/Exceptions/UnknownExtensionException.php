<?php

namespace J0sh0nat0r\SimpleConfig\Exceptions;

class UnknownExtensionException extends \Exception
{
    public function __construct($extension)
    {
        parent::__construct("The extension `.$extension` is not recognised");
    }
}
