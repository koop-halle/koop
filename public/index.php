<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', ROOT_DIR . DIRECTORY_SEPARATOR . 'error.log');

defined('ROOT_DIR') || define('ROOT_DIR', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
require_once ROOT_DIR. DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once ROOT_DIR . DIRECTORY_SEPARATOR . 'doctrineBootstrap.php';
if (true === \Application\Util\DependencyContainer::getInstance()->getConfiguration()->isDevelopment()) {
    ini_set('opcache.revalidate_freq', '0');
    $cacheDir = ROOT_DIR.DIRECTORY_SEPARATOR.'cache';
    function delTree($cacheDir)
    {

        if (true === is_dir($cacheDir)) {
            $files = array_diff(scandir($cacheDir), ['.', '..']);
            foreach ($files as $file) {
                (is_dir("$cacheDir/$file")) ? delTree("$cacheDir/$file") : unlink("$cacheDir/$file");
            }

            return rmdir($cacheDir);
        }

        return true;
    }

    delTree($cacheDir);
    mkdir($cacheDir);
}
\Symfony\Component\Debug\ErrorHandler::register();
\Symfony\Component\Debug\ExceptionHandler::register(true);
\Application\Util\DependencyContainer::getInstance()
                                     ->getApplication()
                                     ->setup()
;
require ROOT_DIR . DIRECTORY_SEPARATOR . 'app.php';
