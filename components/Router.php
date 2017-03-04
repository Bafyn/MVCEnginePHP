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
        $this->uri = $this->get_uri();
        $this->segments = $this->get_segments($this->uri);
        $this->get_controller_and_action($this->segments['address']);
    }

    private function get_uri()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    private function get_segments($uri)
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
        $segments['params']['post'] = $_POST;
        $segments['params']['post']['count'] = count($segments['params']['post']);
        $segments['params']['files'] = $_FILES;

        return $segments;
    }

    private function get_controller_and_action($address)
    {
        $controller_name = 'ErrorController';
        $action_name = 'action_index';

        if (empty($address)) {
            $controller_name = 'MainController';
            $action_name = 'action_index';
        } else {
            $address_array = explode('/', $address);
            $num_of_parts = count($address_array);

            if ($num_of_parts == 1) {
                if ($this->is_action_found($address_array[0], 'index')) {
                    $controller_name = ucfirst($address_array[0]) . 'Controller';
                    $action_name = 'action_index';
                }
            }

            if ($num_of_parts == 2) {
                if ($this->is_action_found($address_array[0], $address_array[1])) {
                    $controller_name = ucfirst($address_array[0]) . 'Controller';
                    $action_name = 'action_' . $address_array[1];
                }
            }
        }

        $this->controller = $controller_name;
        $this->action = $action_name;
        return TRUE;
    }

    public static function header_location($location = '/')
    {
        header("Location: $location");
    }

    public function error404()
    {
        $controller_object = new ErrorController();
        $controller_object->action_index();
    }

    private function is_controller_found($controller_name)
    {
        $controller_file = ROOT . '/controllers/' . $controller_name . '.php';
        return file_exists($controller_file);
    }

    private function is_action_found($controller_name, $action_name)
    {
        $controller_name = ucfirst($controller_name) . 'Controller';
        $action_name = 'action_' . $action_name;
        if ($this->is_controller_found($controller_name)) {
            return method_exists($controller_name, $action_name);
        } else {
            return false;
        }
    }

    public function run()
    {
        // Создать объект, вызвать метод (т.е. action)
        $controller_object = new $this->controller();
        $parameters_array = array(
            'get' => $this->segments['params']['get'],
            'post' => $this->segments['params']['post'],
            'files' => $this->segments['params']['files']);
        $result = $controller_object->{$this->action}($parameters_array);

        if (!$result) {
            $this->error404();
        }
    }

}
