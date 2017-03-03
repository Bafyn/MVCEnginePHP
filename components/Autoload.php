<?php

function __autoload($class_name)
{
    $directories = array(
        '/models/',
        '/components/',
        '/controllers/'
    );

    foreach ($directories as $path) {
        $path = ROOT . $path . $class_name . '.php';

        if (file_exists($path)) {
            include_once($path);
        }
    }
}
