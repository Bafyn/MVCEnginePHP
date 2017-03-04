<?php

class NewsController
{

    public function actionIndex()
    {
        echo 'NewsController actionIndex';
        return TRUE;
    }

    public function actionView($params)
    {
        echo 'NewsController actionView';
        return TRUE;
    }

    public function actionComm($param)
    {
        echo 'NewsController actionComm';
        return TRUE;
    }

}
