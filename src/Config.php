<?php

namespace J0sh0nat0r\SimpleConfig;

use J0sh0nat0r\SimpleConfig\Exceptions\AutoSaveFailedException;
use J0sh0nat0r\SimpleConfig\Exceptions\ConfigLoadFailedException;
use J0sh0nat0r\SimpleConfig\Exceptions\ConfigNotFoundException;
use J0sh0nat0r\SimpleConfig\Exceptions\InvalidFormatException;
use J0sh0nat0r\SimpleConfig\Exceptions\UnknownExtensionException;
use J0sh0nat0r\SimpleConfig\Interfaces\IFormat;

class Config
{
    /**
     * @var IFormat[]
     */
    public static $formats = [
        Formats\PHP::class,
        Formats\Json::class,
        Formats\Yaml::class,
    ];

    /**
     * @var bool
     */
    public $auto_save = true;

    /**
     * @var string
     */
    private $file;
    /**
     * @var IFormat
     */
    private $format;

    /**
     * @var array
     */
    private $values;

    /**
     * Config constructor.
     *
     * @param string $file    Path to the config file
     * @param array  $options Options
     *
     * @throws InvalidFormatException
     * @throws ConfigNotFoundException
     * @throws UnknownExtensionException
     * @throws ConfigLoadFailedException
     */
    public function __construct($file, $options = [])
    {
        if (!is_string($file)) {
            throw new \InvalidArgumentException('The `file` argument must be a string');
        }

        if (!is_array($options)) {
            throw new \InvalidArgumentException('The `options` argument must be an array');
        }

        if (!file_exists($file)) {
            if (!touch($file)) {
                throw new ConfigNotFoundException($file);
            }
        }

        $this->file = $file;

        if (isset($options['format'])) {
            $format = $options['format'];

            if (!class_exists($format)) {
                $this->format = $this->getFormatForExtension($format);

                if (is_null($this->format)) {
                    throw  new InvalidFormatException("`$format` is not a valid format");
                }
            }
        } else {
            $extension = pathinfo($this->file, PATHINFO_EXTENSION);

            $this->format = $this->getFormatForExtension($extension);

            if (is_null($this->format)) {
                throw new UnknownExtensionException($extension);
            }
        }

        if (!class_exists($this->format) || !in_array(IFormat::class, class_implements($this->format))) {
            throw new InvalidFormatException($this->format);
        }

        if (isset($options['auto_save'])) {
            if (!is_bool($options['auto_save'])) {
                throw new \InvalidArgumentException('The `options[auto_save]` option must be a boolean');
            }

            $this->auto_save = $options['auto_save'];
        }

        // Time to load the config, lets hope this all goes well
        $this->reload();
    }

    /**
     * Saves the configuration in its current state.
     *
     * @return bool True on success, False on failure
     */
    public function save()
    {
        return file_put_contents($this->file, ($this->format)::encode($this->values)) !== false;
    }

    /**
     * Reloads the configuration from the disk.
     *
     * @throws ConfigLoadFailedException
     *
     * @return void
     */
    public function reload()
    {
        $config_data = file_get_contents($this->file);

        if ($config_data === false) {
            throw new ConfigLoadFailedException("Failed to load config from: $this->file");
        }

        if (empty($config_data)) {
            $this->values = [];

            return;
        }

        $decoded = ($this->format)::decode($config_data);

        if ($decoded === false) {
            throw new ConfigLoadFailedException('Failed to decode the config file');
        }

        $this->values = empty($decoded) ? [] : $decoded;
    }

    /**
     * Set a value in the config.
     *
     * @param string $key   Key of the
     * @param mixed  $value
     *
     * @throws AutoSaveFailedException
     *
     * @return bool|bool[]
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $values = $key;
            $successes = [];

            foreach ($values as $key => $value) {
                $successes[$key] = $this->set($key, $value);
            }

            return $successes;
        }

        if (!is_string($key)) {
            throw new \InvalidArgumentException('The `key` argument must be an array or a string');
        }

        $keys = preg_split('/(?<!\\\\)(?:\\\\\\\\)*\./', $key);

        $values = &$this->values;
        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($values[$key]) || !is_array($values[$key])) {
                $values[$key] = [];
            }

            $values = &$values[$key];
        }

        $values[array_shift($keys)] = $value;

        $this->tryAutoSave();

        return true;
    }

    /**
     * Get a value from the config.
     *
     * @param string $key   Key of the
     * @param mixed  $value
     *
     * @return bool|bool[]
     */
    public function get($key, $value = null)
    {
        if (is_array($key)) {
            $values = $key;
            $results = [];

            foreach ($values as $key => $value) {
                $results[$key] = $this->get($key, $value);
            }

            return $results;
        }

        if (!is_string($key)) {
            throw new \InvalidArgumentException('The `key` argument must be an array or a string');
        }

        $keys = preg_split('/(?<!\\\\)(?:\\\\\\\\)*\./', $key);

        $value = &$this->values;
        foreach ($keys as $key) {
            $value = $value[$key];
        }

        return $value;
    }

    /**
     * Try to auto save.
     *
     * @throws AutoSaveFailedException
     */
    private function tryAutoSave()
    {
        if (!$this->auto_save) {
            return;
        }

        if (!$this->save()) {
            throw new AutoSaveFailedException();
        }
    }

    /**
     * Attempts to find an `IFormat` implementor for the given extension.
     *
     * @param $extension
     *
     * @return IFormat|null
     */
    private function getFormatForExtension($extension)
    {
        foreach ($this::$formats as $format) {
            if ($format::supports($extension)) {
                return $format;
            }
        }
    }
}
