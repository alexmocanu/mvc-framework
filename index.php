<?php

session_start();

/**
 * constant used to prevent direct access to scripts
 */
define('APP_MVC','1');

include('Application\config.php');
include('System\MVC_Exception.php');
date_default_timezone_set($config['timezone']);


/**
 * Class MVC
 * Initialize the autoloader, route URIs and load views
 *
 * @property object $config Application configuration
 * @property string $appDir Application directory
 */
class MVC
{
    protected $config;
    protected $appDir = 'Application';

    public function __construct()
    {
        global $config;

        $this->config = (object)$config;
        spl_autoload_register(array($this, 'loader'));
    }

    /**
     * Classes autoloader
     *
     * @param $class
     *
     * @throws MVC_Exception
     */
    public function loader($class)
    {
        $classToLoad = '';

        if ($this->stringEndsWith($class, '_controller')) {
            $classToLoad = $this->appDir . '\\Controllers\\' . $class;
        }

        if ($this->stringEndsWith($class, '_model')) {
            $classToLoad = $this->appDir . '\\Models\\' . $class;
        }

        if (empty($classToLoad)) {
            throw new MVC_Exception('Invalid class name ' . $class);
        }

        $classToLoad .= '.php';

        if (!file_exists($classToLoad)) {
            throw new MVC_Exception('Unable to load class ' . $class);
        }

        include($classToLoad);
    }

    /**
     * Check if string ends with substring
     *
     * @param $whole
     * @param $end
     *
     * @return bool
     */
    protected function stringEndsWith($whole, $end)
    {
        return (strpos($whole, $end, strlen($whole) - strlen($end)) !== false);
    }

    /**
     * Route URI's
     * A standard URI has this format: controller/method/var1/var2/...
     *
     * @throws MVC_Exception
     */
    public function route()
    {
        $uri = str_replace(
            array(
                $this->config->base_url,
                basename(__FILE__) . '/'
            ),
            '',
            $_SERVER['REQUEST_URI']
        );
        $uri = explode('/', $uri);

        $controller = $this->config->default_controller;
        $method     = $this->config->default_method;

        if (!empty($uri[0])) {
            $controller = ucfirst(strtolower($uri[0] . '_controller'));
        }

        if (!empty($uri[1])) {
            $method = ucfirst(strtolower($uri[1]));
        }

        if (!class_exists($controller)) {
            throw new MVC_Exception("Unable to load controller: " . $controller);
        }

        $controller = new $controller();

        if (!method_exists($controller, $method)) {
            throw new MVC_Exception("Unable to load method: " . $method);
        }

        $parameters = (count($uri) > 2) ? array_slice($uri, 2) : array();

        call_user_func(array($controller, $method), $parameters);
    }

    /**
     * Load a view file
     *
     * @param       $viewName Name of the view file
     * @param array $params array of parameters to pass to the view.
     *
     * @throws MVC_Exception
     */
    public function loadView($viewName, $params = array())
    {
        $view = $this->appDir . '\\Views\\' . $viewName . '.php';

        if (!file_exists($view)) {
            throw new MVC_Exception("Unable to load view: " . $view);
        }

        if (count($params) > 0) {
            extract($params);
        }

        require_once($view);
    }
}

try {
    $mvc = new MVC();
    $mvc->route();
} catch (MVC_Exception $e) {
    echo "Fatal Error: " . $e->getMessage();
    die;
}



