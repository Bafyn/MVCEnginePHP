<?php

class Router
{

    private $routes;
    private $uri;
    private $segments;
    private $controller;
    private $action;

    public function __construct()
    {
        $routesPath = ROOT . '\config\routes.php';
        $this->routes = include($routesPath);
        $this->uri = $this->getURI();
        $this->segments = $this->getSegments($this->uri);
        $this->getControllerAndAction($this->segments['address']);
    }

    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    private function getSegments($uri)
    {
        $segments = array(
            'params' => array(
                'get' => array(),
                'post' => array(),
                'files' => array()
            ),
            'address' => '',
        );

        $params_string = '';
        if (($pos = strpos($uri, "?")) !== false) {
            $segments['address'] = trim(substr($uri, 0, $pos));
            $params_string = substr($uri, $pos);
            $params_string = str_ireplace(array('?', '/', '\\', '\'', '"', '~', '*'), '', trim($params_string));
            $params_string = trim($params_string, '&');
        } else {
            $segments['address'] = $uri;
        }

        if (!empty($params_string)) {
            parse_str($params_string, $segments['params']['get']);
            $segments['params']['get']['count'] = count($segments['params']['get']);
            //$segments['params']['get']['param_string'] = $params_string;
        }
        echo 'params: ' . $params_string . '<br/>';
        $segments['params']['post'] = $_POST;
        $segments['params']['post']['count'] = count($segments['params']['post']);
        $segments['params']['files'] = $_FILES;

        return $segments;
    }

    private function getControllerAndAction($address)
    {
        $controller_name = 'ErrorController';
        $action_name = 'action404';

        if (empty($address)) {
            echo 'empty<br/>';
            $controller_name = 'NewsController';
            $action_name = 'actionIndex';
        } else {
            echo 'not empty<br/>';
            $address_array = explode('/', $address);
            $num_of_parts = count($address_array);

            if ($num_of_parts == 1) {
                echo 'num 1<br/>';
                if ($this->isActionFounded($address_array[0], 'index')) {
                    $controller_name = ucfirst($address_array[0]) . 'Controller';
                    $action_name = 'actionIndex';
                }
            }

            if ($num_of_parts == 2) {
                echo 'num 2<br/>';
                if ($this->isActionFounded($address_array[0], $address_array[1])) {
                    $controller_name = ucfirst($address_array[0]) . 'Controller';
                    $action_name = 'action' . ucfirst($address_array[1]);
                }
            }
        }

        echo 'action: ' . $action_name . '<br/>';
        echo 'controller: ' . $controller_name . '<br/>';
        echo 'address: ' . $this->segments['address'] . '<br/><br/>';
        $this->controller = $controller_name;
        $this->action = $action_name;
        return TRUE;
    }

    public static function headerLocation($location = '/')
    {
        header("Location: $location");
    }

    public function error404()
    {
        $controller_object = new ErrorController();
        $controller_object->action404();
    }

    private function isControllerFounded($controller_name)
    {
        $controller_file = ROOT . '/controllers/' . $controller_name . '.php';
        return file_exists($controller_file);
    }

    private function isActionFounded($controller_name, $action_name)
    {
        $controller_name = ucfirst($controller_name) . 'Controller';
        $action_name = 'action' . ucfirst($action_name);
        if ($this->isControllerFounded($controller_name)) {
            return method_exists($controller_name, $action_name);
        } else {
            return false;
        }
    }

    public function Run()
    {
        // Создать объект, вызвать метод (т.е. action)
        $controller_object = new $this->controller();
        $parameters_array = array(
            'get' => $this->segments['params']['get'],
            'post' => $this->segments['params']['post'],
            'files' => $this->segments['params']['files']);
        $result = $controller_object->{$this->action}($parameters_array);

        if (!$result) {
            echo 'No result<br/>';
            $this->error404();
        }
    }

}
