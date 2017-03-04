<?php

class ErrorController
{

    public function action404()
    {
        require_once(ROOT . '/views/errors/404.php');
        return true;
    }

}
