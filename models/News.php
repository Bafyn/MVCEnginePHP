<?php

class News
{

    /**
     * Returns single news item with specified id
     * @param integer $id
     */
    public static function getNewsItemById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM news WHERE id=:id";
        $result = $GLOBALS['DBH']->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);

        if ($result) {
            return $result->fetch();
        }
    }

    /**
     * Returns an array of news items
     */
    public static function getNewsList()
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
