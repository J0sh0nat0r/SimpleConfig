<?php

namespace J0sh0nat0r\SimpleConfig;

use J0sh0nat0r\SimpleConfig\Interfaces\IFormat;

/**
 * Base class for formats, provides a basic implementation of the supports method.
 */
abstract class FormatBase implements IFormat
{
    protected static $supported_extensions = [];

    public static function supports($extension)
    {
        return in_array(ltrim($extension, '.'), static::$supported_extensions);
    }
}
