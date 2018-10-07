<?php

namespace J0sh0nat0r\SimpleConfig;

/**
 * A static wrapper around a SimpleConfig instance (e.g for a global config).
 *
 * @method  static bool        save()
 * @method  static bool        reload()
 * @method  static bool|bool[] set(string|string[] $key, mixed $value = null)
 * @method  static mixed       get(string|string[] $key, mixed $default = null)
 */
class StaticFacade
{
    /**
     * Config instance for the static facade.
     *
     * @var Config
     */
    private static $config;

    /**
     * Handle static calls and proxy them to `$config`.
     *
     * @param string     $name      Name of the function being called
     * @param array|null $arguments Arguments passed ot the function being called
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        self::_checkBound();

        if (method_exists(self::$config, $name)) {
            return call_user_func_array([self::$config, $name], $arguments);
        }

        throw new \BadMethodCallException();
    }

    /**
     * Bind the `StaticFacade` to an instance of `Config`.
     *
     * @param Config $config The `Config` instance to bind to
     */
    public static function bind(Config $config)
    {
        self::$config = $config;
    }

    /**
     * Checks if the `StaticFacade` has been bound to a `Config` instance,
     * and, if not, throws a `\Exception`.
     *
     * @throws \Exception
     *
     * @return void
     */
    private static function _checkBound()
    {
        if (!isset(self::$config)) {
            throw new \Exception(
                'Please bind the `StaticFacade` to an instance of `Config` with the `bind` method'
            );
        }
    }
}
