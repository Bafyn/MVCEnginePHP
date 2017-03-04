<?php

class MainController extends Controller
{

    function __construct()
    {
        $this->model = new Main();
        $this->view = new View();
    }

    public function action_index()
    {
        $data = $this->model->get_data();
        $this->view->generate('main_view.php', $data);
        return TRUE;
    }

}
