<?php

class Main extends Model
{

    /**
     * Returns an array of news items
     */
    public function get_data()
    {
        $news_list = array();
        $sql = "SELECT * FROM news";
        $result = $GLOBALS['DBH']->prepare($sql);
        $result->execute();
        $i = 0;
        if ($result) {
            while ($row = $result->fetch()) {
                $news_list[$i]['id'] = $row['id'];
                $news_list[$i]['title'] = $row['title'];
                $news_list[$i]['content'] = $row['content'];
                $news_list[$i]['pub_date'] = $row['pub_date'];
                $i++;
            }

            return $news_list;
        }

        return NULL;
    }

}
