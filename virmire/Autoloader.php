<?php declare(strict_types = 1);

namespace Virmire;

require_once __DIR__ . '/Traits/Singleton.php';

use Virmire\Traits\Singleton;

/**
 * Class Autoloader
 *
 * @package Virmire
 */
class Autoloader
{
    use Singleton;

    /**
     * @var array
     */
    protected $classMap = [];

    /**
     * @return void
     */
    protected function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    protected function init()
    {
        $this->register();
    }

    /**
     * @param string $prefix
     * @param string $baseDir
     * @param bool $isPrepend
     *
     * @return void
     */
    public function addNamespace(string $prefix, string $baseDir, bool $isPrepend = false)
    {
        $prefix = trim($prefix, '\\') . '\\';

        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';

        if (isset($this->classMap[$prefix]) === false) {
            $this->classMap[$prefix] = array();
        }

        if ($isPrepend) {
            array_unshift($this->classMap[$prefix], $baseDir);
        } else {
            array_push($this->classMap[$prefix], $baseDir);
        }
    }

    /**
     * @param string $class
     *
     * @return string
     */
    public function loadClass(string $class) : string
    {
        $prefix = $class;

        while (false !== $pos = strrpos($prefix, '\\')) {
            $prefix = substr($class, 0, $pos + 1);

            $relativeClass = substr($class, $pos + 1);

            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);
            if (!empty($mappedFile)) {
                return $mappedFile;
            }

            $prefix = rtrim($prefix, '\\');
        }

        return "";
    }

    /**
     * @param string $prefix
     * @param string $relativeClass
     *
     * @return string
     */
    protected function loadMappedFile(string $prefix, string $relativeClass) : string
    {
        if (isset($this->classMap[$prefix]) === false) {
            return '';
        }

        foreach ($this->classMap[$prefix] as $baseDir) {
            $file = sprintf('%s%s.php', $baseDir, str_replace('\\', '/', $relativeClass));

            if ($this->requireFile($file)) {
                return $file;
            }
        }

        return '';
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    protected function requireFile(string $file) : bool
    {
        if (file_exists($file)) {
            /** @noinspection PhpIncludeInspection */
            require $file;

            return true;
        }

        return false;
    }

}
