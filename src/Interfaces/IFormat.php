<?php

namespace J0sh0nat0r\SimpleConfig\Interfaces;

/**
 * Format interface.
 */
interface IFormat
{
    /**
     * Checks if an extension ($extension) is supported.
     *
     * @param string $extension Extension to check
     *
     * @return bool Whether or not the format is supported
     */
    public static function supports($extension);

    /**
     * Encodes data in the format.
     *
     * @param array $data Array of data to encode
     *
     * @return string|false Encoded data (or False on failure)
     */
    public static function encode($data);

    /**
     * Decode data encoded in the format.
     *
     * @param string $data String of encoded data to decode
     *
     * @return array|false Decoded data held in an associative array (or False on failure)
     */
    public static function decode($data);
}
