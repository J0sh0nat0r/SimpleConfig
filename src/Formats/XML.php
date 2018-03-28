<?php

namespace J0sh0nat0r\SimpleConfig\Formats;

use J0sh0nat0r\SimpleConfig\FormatBase;

class XML extends FormatBase
{
    protected static $supported_extensions = [
        'xml',
    ];

    /**
     * {@inheritdoc}
     */
    public static function encode($data)
    {
        return xmlrpc_encode($data);
    }

    /**
     * {@inheritdoc}
     */
    public static function decode($data)
    {
        return xmlrpc_decode($data);
    }
}
