<?php
namespace Transformatika\Config;

class Config
{
    protected $config;

    protected $configDir;

    public function __construct()
    {
        if ($this->config === null) {
            $this->configDir = $this->getRootDir() . DS . 'storage' . DS . 'etc';
            $objConfig = simplexml_load_file($this->configDir . DS . 'app.xml');
            $this->config = json_decode(json_encode($objConfig), true);
        }
    }

    /**
     * Read XML Config File
     *
     * example: readConfigFile('conf.d/usergroup.xml');
     *
     * @param string $path
     * @return SimpleXMLElement
     */
    public function readConfigFile($path = '', $resultAsObj = false)
    {
        $realPath = $this->configDir . DS . str_replace('/', DS, $path);
        if (file_exists($realPath)) {
            $confArray = simplexml_load_file($realPath);
            if ($resultAsObj === true) {
                return $confArray;
            } else {
                return json_decode(json_encode($confArray), true);
            }
        }
    }

    /**
     * Get Config Directory
     *
     * @return string
     */
    public function getConfigDir()
    {
        return $this->configDir;
    }

    /**
     * Get Config
     *
     * @param string $key
     */
    public function getConfig($key = '')
    {
        if (empty($key)) {
            return $this->config;
        } else {
            $keys = str_replace('\\', '/', $key);
            $explodeKey = explode('/', $keys);
            if (array_key_exists($explodeKey[0], $this->config)) {
                if (isset($explodeKey[1]) && isset($this->config[$explodeKey[0]][$explodeKey[1]])) {
                    if (isset($explodeKey[2]) && isset($this->config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]])) {
                        if (isset($explodeKey[3]) && isset($this->config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]])) {
                            if (isset($explodeKey[4]) && isset($this->config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]][$explodeKey[4]])) {
                                if (isset($explodeKey[5]) && isset($this->config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]][$explodeKey[4]][$explodeKey[5]])) {
                                    return $this->config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]][$explodeKey[4]][$explodeKey[5]];
                                } else {
                                    return $this->config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]][$explodeKey[4]];
                                }
                            } else {
                                return $this->config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]];
                            }
                        } else {
                            return $this->config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]];
                        }
                    } else {
                        return $this->config[$explodeKey[0]][$explodeKey[1]];
                    }
                } else {
                    return $this->config[$explodeKey[0]];
                }
            }
        }

    }

    /**
     * Get root dir
     *
     * @return string
     */
    public function getRootDir()
    {
        return realpath(__DIR__ . DS . '..' . DS . '..' . DS . '..' . DS . '..' . DS . '..' . DS . '..');
    }
}
