<?php

class NewsController
{

    public function actionIndex()
    {
        $news_list = News::getNewsList();

        print_r($news_list);

        echo 'NewsController actionIndex';
        return TRUE;
    }

    public function actionView($params)
    {
        if ($params[0] != '35') {
//            Router::headerLocation('/404');
        }



        echo 'NewsController actionView';
        return TRUE;
    }

}
