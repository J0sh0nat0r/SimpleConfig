<?php

namespace J0sh0nat0r\SimpleConfig\Formats;

use J0sh0nat0r\SimpleConfig\FormatBase;

class Json extends FormatBase
{
    /**
     * {@inheritdoc}
     */
    protected static $supported_extensions = [
        'js',
        'json',
    ];

    /**
     * {@inheritdoc}
     */
    public static function encode($data)
    {
        return json_encode($data);
    }

    /**
     * {@inheritdoc}
     */
    public static function decode($data)
    {
        return json_decode($data, true);
    }
}
