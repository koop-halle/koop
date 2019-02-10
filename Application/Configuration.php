<?php

namespace Application;

/**
 * Class Configuration
 *
 * @package Application
 */
class Configuration
{
    const KEY_APPLICATION_NAME     = 'KEY_APPLICATION_NAME';

    const KEY_APPLICATION_URL      = 'KEY_APPLICATION_URL';

    const KEY_IS_DEVELOPMENT       = 'KEY_IS_DEVELOPMENT';

    /**
     * @var string
     */
    protected $pathToFile;

    /**
     * @var array
     */
    protected $configuration;


    /**
     * Configuration constructor.
     *
     * @param string | null $pathToFile
     *
     * @throws \Exception
     */
    public function __construct($pathToFile = null)
    {
        if (null === $pathToFile) {
            $pathToFile = ROOT_DIR . DIRECTORY_SEPARATOR . 'applicationConfiguration.php';
        }
        if (false === file_exists($pathToFile)) {
            throw new \InvalidArgumentException('can not find configuration file under ' . $pathToFile);
        }
        $this->pathToFile = $pathToFile;
        $this->readConfiguration();
    }


    /**
     * @return $this
     */
    protected function readConfiguration()
    {
        $this->configuration = require $this->pathToFile;

        return $this;
    }


    /**
     * @return bool
     */
    public function isDevelopment()
    {
        return true === $this->get(self::KEY_IS_DEVELOPMENT);
    }


    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        if (false === array_key_exists($key, $this->configuration)) {
            throw new \InvalidArgumentException('key "' . $key . '" is not configured!');
        }

        return $this->configuration[$key];
    }




    /**
     * @return string
     */
    public function getApplicationName()
    {
        return $this->get(self::KEY_APPLICATION_NAME);
    }


    /**
     * @return string
     */
    public function getApplicationUrl()
    {
        return $this->get(self::KEY_APPLICATION_URL);
    }
}