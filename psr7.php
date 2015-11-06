<?php

use Zend\Stratigility\MiddlewarePipe;
use Zend\Diactoros\Server;

require __DIR__ . '/../../vendor/autoload.php';

/**
 * Use middle ware to determine which MVC to use, cake or ZF2
 */
$app    = new MiddlewarePipe();
$server = Server::createServer($app, $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

/**
 * This runs a complete bootstrap of ZF2 without running the MVC
 */
$bootstrapZF2 = function()
{
    chdir(dirname(__DIR__ . '/../../../'));
    // Decline static file requests back to the PHP built-in webserver
    if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
        return false;
    }
    if (!file_exists('vendor/autoload.php')) {
        throw new RuntimeException(
            'Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.'
        );
    }
    // Setup autoloading
    include 'vendor/autoload.php';
    if (!defined('APPLICATION_PATH')) {
        define('APPLICATION_PATH', realpath(__DIR__ . '/../../'));
    }
    $appConfig = include APPLICATION_PATH . '/config/application.config.php';
    if (file_exists(APPLICATION_PATH . '/config/development.config.php')) {
        $appConfig = Zend\Stdlib\ArrayUtils::merge($appConfig, include APPLICATION_PATH . '/config/development.config.php');
    }

    // Load console/http specific configurations
    if (\Zend\Console\Console::isConsole()) {
        if (file_exists(APPLICATION_PATH . '/config/console.config.php')) {
            $appConfig = Zend\Stdlib\ArrayUtils::merge($appConfig, include APPLICATION_PATH . '/config/console.config.php');
        }
    } else {
        if (file_exists(APPLICATION_PATH . '/config/http.config.php')) {
            $appConfig = Zend\Stdlib\ArrayUtils::merge($appConfig, include APPLICATION_PATH . '/config/http.config.php');
        }
    }
    // Some OS/Web Server combinations do not glob properly for paths unless they
    // are fully qualified (e.g., IBM i). The following prefixes the default glob
    // path with the value of the current working directory to ensure configuration
    // globbing will work cross-platform.
    if (isset($appConfig['module_listener_options']['config_glob_paths'])) {
        foreach ($appConfig['module_listener_options']['config_glob_paths'] as $index => $path) {
            if ($path !== 'config/autoload/{,*.}{global,local}.php') {
                continue;
            }
            $appConfig['module_listener_options']['config_glob_paths'][$index] = getcwd() . '/' . $path;
        }
    }

    // Run the application!
    return Zend\Mvc\Application::init($appConfig);
};

/**
 * Create a service manager aware fixture then run cake.php as expected
 */
$runCake = function() use ($bootstrapZF2)
{
    global $getServiceManager;

    $application = $bootstrapZF2();
    $getServiceManager = function() use ($application){
        return $application->getServiceManager();
    };

/**
 * // To access doctrine through the service manager within cake
 * global $getServiceManager;
 * $entityManager = $getServiceManager()->get('doctrine.entitymanager.orm_default');
 */

    require __DIR__ . '/cake.php';
};

/**
 * Run the ZF2 application
 */
$runZF2 = function() use ($bootstrapZF2)
{
    $application = $bootstrapZF2();
    $application
        ->getServiceManager()
        ->get('Zend\Session\SessionManager')
        ->setName('CAKEPHP')
        ->start();
    $bootstrapZF2()->run();
};

// Run
$app->pipe('/rentals-united/live-notification', function ($req, $res, $next) use ($runZF2) {
    $runZF2();
});

// Run
$app->pipe('/rpc/property-calendar/rate', function ($req, $res, $next) use ($runZF2) {
    $runZF2();
});


$server->listen($runCake);

