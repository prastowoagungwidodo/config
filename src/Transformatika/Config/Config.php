<?php
/**
 * Config
 *
 * MVC Configuration
 * ini sebenarnya hanyalah pembaca file saja
 * tapi kenapa dinamakan Config ya????
 * saya juga bingung kenapa bisa ngasi nama gitu.
 *
 * @category  MVC
 * @package   Config
 * @author    Prastowo aGung Widodo <agung@transformatika.com>
 * @copyright 2016 PT Daya Transformatika
 * @license   MIT
 * @version   GIT: $Id$
 * @link      https://github.com/transformatika/config.git
 */
namespace Transformatika\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * Config Class
 *
 * Handle configuration file
 *
 * @category  MVC
 * @package   Config
 * @author    Prastowo aGung Widodo <agung@transformatika.com>
 * @copyright 2016 PT Daya Transformatika
 * @license   MIT
 * @version   GIT: $Id$
 * @link      https://github.com/transformatika/mvc.git
 */
class Config
{
    protected static $config;

    protected static $configDir;

    protected static $configExt = 'php';

    protected static $srcPath = 'app';

    protected static $storagePath = 'storage';

    protected static $cachePath;

    protected static $rootDir;

    public static function init($option = array())
    {
        self::$rootDir = self::getRootDir();
        if (self::$config === null) {
            if (isset($option['configExt'])) {
                self::$configExt = $option['configExt'];
            }
            if (isset($option['storagePath'])) {
                self::$storagePath = $option['storagePath'];
            }
            if (isset($option['srcPath'])) {
                self::$srcPath = $option['srcPath'];
            }
            if (isset($option['configDir'])) {
                self::$configDir = $option['configDir'];
            } else {
                self::$configDir = self::$rootDir . DIRECTORY_SEPARATOR . self::$storagePath . DIRECTORY_SEPARATOR . 'config';
            }

            if (isset($option['cachePath'])) {
                self::$cachePath = self::$rootDir.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $option['cachePath']).DIRECTORY_SEPARATOR.'config';
            } else {
                self::$cachePath = self::$rootDir.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'config';
            }

            $option['cache'] = isset($option['cache']) ? $option['cache'] : true;

            if (!is_dir(self::$cachePath)) {
                $createDir = self::createDir(self::$cachePath);
                var_dump($createDir);exit();
            }

            $cachedConfig = self::$cachePath.DIRECTORY_SEPARATOR.'app.php';
            if (file_exists($cachedConfig) && $option['cache'] === true) {
                self::$config = require_once $cachedConfig;
            } else {
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
                $str = "<?php\nreturn ".var_export(self::$config, true).";\n";
                touch($cachedConfig);
                file_put_contents($cachedConfig, $str);
            }
        }
    }

    protected static function createDir($path, $includeFileName = false)
    {
        $dirpath = str_replace('/', DIRECTORY_SEPARATOR, rawurldecode($path));
        $dirpath = str_replace(self::$rootDir, '', $dirpath);
        $dir = explode(DIRECTORY_SEPARATOR, $dirpath);
        $total = (int)count($dir);

        if ($includeFileName == true) {
            unset($dir[($total - 1)]);
        }
        $currentDirectory = self::$rootDir;
        $error = 0;
        foreach ($dir as $key) {// Membuat direktori
            if ($key != '' && !is_dir($currentDirectory . $key)) {
                $oldumask = umask(0);
                $m = mkdir($currentDirectory . $key, 0777);
                if (!$m) {
                    $error++;
                }
                umask($oldumask);
            }
            $currentDirectory .= $key . DIRECTORY_SEPARATOR;
        }
        if ($error > 0) {
            return false;
        } else {
            return true;
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
    public static function readConfigFile($path = '')
    {
        $path = str_replace('..', '', $path);
        $realPath = self::$configDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
        $cachedFile = self::$cachePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path).'.php';
        if (file_exists($cachedFile) && self::$config['cache'] === true) {
            $conf = require_once($cachedFile);
        } else {
            if (file_exists($realPath)) {
                switch (self::$configExt) {
                    case 'xml':
                        $objConfig = simplexml_load_file($realPath);
                        $conf = json_decode(json_encode($objConfig), true);
                        break;
                    case 'yaml':
                        $conf = Yaml::parse(file_get_contents($realPath));
                        break;
                    default:
                        $conf = require_once $realPath;
                        break;
                }
                if (self::$config['cache'] === true) {
                    touch($cachedFile);
                    $str = "<?php\nreturn ".var_export($conf, true).";\n";
                    file_put_contents($cachedFile, $str);
                }
            }
        }
        return $conf;
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
