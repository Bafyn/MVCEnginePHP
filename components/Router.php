<?php

class Router
{

    private $routes;
    private $uri;
    private $segments;

    public function __construct()
    {
        $routesPath = ROOT . '\config\routes.php';
        $this->routes = include($routesPath);
        $this->uri = $this->getURI();
        $this->segments = $this->getSegments($this->uri);
    }

    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public static function headerLocation($location = '/')
    {
        header("Location: $location");
    }

    public function error404()
    {
        require_once(ROOT . '/controllers/ErrorController.php');
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
        //$controller_name = ucfirst($controller_name) . 'Controller';
        //$action_name = 'action' . ucfirst($action_name);
        if ($this->isControllerFounded($controller_name)) {
            return method_exists($controller_name, $action_name);
        } else {
            return false;
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
        $segments['params']['post'] = $_POST;
        $segments['params']['post']['count'] = count($segments['params']['post']);
        $segments['params']['files'] = $_FILES;

        return $segments;
    }

    public function Run()
    {
        // Получить строку запроса
        $uri = $this->getURI();
        $is_correct_uri = FALSE;
        // Проверить наличие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path) {
            // Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) {
                // Получаем внутренний путь из внешнего согласно правилу
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                echo 'internal route: ';
                print_r($internalRoute);
                echo '<br/>';

                // Если есть совпадение, определить какой контроллер и action обрабатывают запрос
                $num_of_parts = count($segments);
                $controller_name = array_shift($segments) . 'Controller';
                $controller_name = ucfirst($controller_name);
                $action_name = "action" . ucfirst(array_shift($segments));
                // Получить оставшиеся параметры
                $parameters = $segments;
                echo 'parameters: ';
                print_r($parameters);
                echo '<br/>Controller name: ' . $controller_name . '<br/>';
                echo 'Action name: ' . $action_name . '<br/><br/><br/>';
                // Подключить файл класса-контроллера
                if (!$this->isActionFounded($controller_name, $action_name)) {
                    $this->error404();
                    break;
                }

                // Создать объект, вызвать метод (т.е. action)
                $controller_object = new $controller_name;

                $result = $controller_object->$action_name($parameters);

                if ($result != NULL) {
                    $is_correct_uri = TRUE;
                    break;
                }

//                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
            }
        }

        if (!$is_correct_uri) {
            $this->error404();
        }
    }

}
