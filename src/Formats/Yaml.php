<?php

namespace J0sh0nat0r\SimpleConfig\Formats;

use J0sh0nat0r\SimpleConfig\FormatBase;

class Yaml extends FormatBase
{
    /**
     * {@inheritdoc}
     */
    protected static $supported_extensions = [
        'yml',
        'yaml',
    ];

    /**
     * {@inheritdoc}
     */
    public static function encode($data)
    {
        return yaml_emit($data);
    }

    /**
     * {@inheritdoc}
     */
    public static function decode($data)
    {
        return yaml_parse($data);
    }
}
