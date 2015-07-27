<?php
namespace Transformatika\Config;

/**
 * Use XML instead of YAML file
 */
class Config
{
    protected static $config;

    protected static $configDir;

    public static function init()
    {
        if (self::$config === null) {
            self::$configDir = self::getRootDir() . DS . 'storage' . DS . 'etc';
            $objConfig = simplexml_load_file(self::$configDir . DS . 'app.xml');
            self::$config = json_decode(json_encode($objConfig), true);
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
    public static function readConfigFile($path = '', $resultAsObj = false)
    {
        $realPath = self::$configDir . DS . str_replace('/', DS, $path);
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
    public static function getConfigDir()
    {
        return self::$configDir;
    }

    /**
     * Get Config
     *
     * @param string $key
     */
    public static function getConfig($key = '')
    {
        if (empty($key)) {
            return self::$config;
        } else {
            $keys = str_replace('\\', '/', $key);
            $explodeKey = explode('/', $keys);
            if (array_key_exists($explodeKey[0], self::$config)) {
                if (isset($explodeKey[1]) && isset(self::$config[$explodeKey[0]][$explodeKey[1]])) {
                    if (isset($explodeKey[2]) && isset(self::$config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]])) {
                        if (isset($explodeKey[3]) && isset(self::$config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]])) {
                            if (isset($explodeKey[4]) && isset(self::$config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]][$explodeKey[4]])) {
                                if (isset($explodeKey[5]) && isset(self::$config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]][$explodeKey[4]][$explodeKey[5]])) {
                                    return self::$config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]][$explodeKey[4]][$explodeKey[5]];
                                } else {
                                    return self::$config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]][$explodeKey[4]];
                                }
                            } else {
                                return self::$config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]][$explodeKey[3]];
                            }
                        } else {
                            return self::$config[$explodeKey[0]][$explodeKey[1]][$explodeKey[2]];
                        }
                    } else {
                        return self::$config[$explodeKey[0]][$explodeKey[1]];
                    }
                } else {
                    return self::$config[$explodeKey[0]];
                }
            }
        }

    }

    /**
     * Get root dir
     *
     * @return string
     */
    public static function getRootDir()
    {
        return realpath(__DIR__ . DS . '..' . DS . '..' . DS . '..' . DS . '..' . DS . '..' . DS . '..');
    }
}
