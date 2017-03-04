<?php

class ErrorController extends Controller
{

    function __construct()
    {
        $this->view = new View();
    }

    public function action_index()
    {
        $this->view->generate('errors/404.php');
        return true;
    }

}
