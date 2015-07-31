<?php
namespace Transformatika\Config;

use Symfony\Component\Yaml\Yaml;

class Config
{
    protected static $config;

    protected static $configDir;

    protected static $configExt = 'php';

    protected static $srcPath = 'app';

    protected static $storagePath = 'storage';

    public static function init($option = array())
    {
        if (self::$config === null) {
            if (isset($option['storagePath'])) {
                self::$configExt = $option['configExt'];
            }
            if (isset($option['storagePath'])) {
                self::$storagePath = $option['storagePath'];
            }
            if (isset($option['srcPath'])) {
                self::$srcPath = $option['srcPath'];
            }
            self::$configDir = self::getRootDir() . DIRECTORY_SEPARATOR . self::$storagePath . DIRECTORY_SEPARATOR . 'config';
            switch (self::$configExt) {
                case 'xml':
                    $objConfig = simplexml_load_file(self::$configDir . DIRECTORY_SEPARATOR . 'app.xml');
                    self::$config = json_decode(json_encode($objConfig), true);
                    break;
                case 'yaml':
                    self::$config = Yaml::parse(file_get_contents(self::$configDir.DIRECTORY_SEPARATOR.'app.yaml'));
                    break;
                default:
                    self::$config = require_once self::$configDir.DIRECTORY_SEPARATOR.'app.php';
                    break;
            }


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
        $realPath = self::$configDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
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
        return realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
    }
}