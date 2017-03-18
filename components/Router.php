<?php

class Router
{

    private $uri;
    private $address;
    //private $segments;
    private $controller;
    private $action;

    public function __construct()
    {
        $this->uri = $this->get_uri();
        $this->address = $this->get_address($this->uri);
        $this->get_controller_and_action($this->address);
    }

    /**
     * 
     * Returns uri of the request with parematers
     * 
     * @return string
     */
    private function get_uri()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    /**
     * 
     * Returns uri of the request without parematers
     * 
     * @param string  $uri
     * @return string
     */
    private function get_address($uri)
    {
        $address = $uri;

        if (($pos = strpos($uri, "?")) !== false) {
            $address = trim(substr($uri, 0, $pos));
        }

        return $address;
    }

    /**
     * 
     * Returns name of the controller and action method
     * 
     * @param string $address
     */
    private function get_controller_and_action($address)
    {
        $controller_name = 'ErrorController';
        $action_name = 'action_index';

        if (empty($address)) {
            $controller_name = 'MainController';
        } else {
            $address_array = explode('/', $address);
            $num_of_parts = count($address_array);

            if ($num_of_parts == 1) {
                if ($this->is_action_found($address_array[0], 'index')) {
                    $controller_name = ucfirst($address_array[0]) . 'Controller';
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
    }

    /**
     * 
     * Checks if controller exists
     * 
     * @param string $controller_name
     * @return bool
     */
    private function is_controller_found($controller_name)
    {
        $controller_file = ROOT . '/controllers/' . $controller_name . '.php';
        return file_exists($controller_file);
    }

    /**
     * 
     * Checks if action method exists
     * 
     * @param string $controller_name
     * @param string $action_name
     * @return boolean
     */
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

    /**
     * 
     * Changes location of the page
     * 
     * @param string $location
     */
    public static function header_location($location = '/')
    {
        header("Location: $location");
    }

    /**
     * 
     *  Invoke error
     */
    public function error404()
    {
        $controller_object = new ErrorController();
        $controller_object->action_index();
    }

    /**
     * 
     * Starts the router
     */
    public function run()
    {
        $controller_object = new $this->controller();
        $result = $controller_object->{$this->action}();

        if (!$result) {
            $this->error404();
        }
    }

}
