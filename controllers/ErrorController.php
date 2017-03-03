<?php

class ErrorController
{

    public function action404()
    {
        require_once(ROOT . '/views/errors/404.php');
//        header('Location: /404');
        return true;
    }

}
