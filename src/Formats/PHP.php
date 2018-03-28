<?php

namespace J0sh0nat0r\SimpleConfig\Formats;

use J0sh0nat0r\SimpleConfig\FormatBase;

class PHP extends FormatBase
{
    /**
     * {@inheritdoc}
     */
    protected static $supported_extensions = [
        'php',
    ];

    /**
     * {@inheritdoc}
     */
    public static function encode($data)
    {
        return '<?php'.PHP_EOL.'return '.var_export($data, true).';';
    }

    /**
     * {@inheritdoc}
     */
    public static function decode($data)
    {
        $file = tempnam(sys_get_temp_dir(), 'scf');

        file_put_contents($file, $data);

        $config = require $file;

        unlink($file);

        return $config;
    }
}
