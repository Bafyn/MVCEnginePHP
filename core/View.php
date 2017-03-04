<?php

class View
{

    public $template_view = 'template_view.php'; // общий вид по умолчанию;

    /**
     * Генерирует внешний вид страницы
     * @param string $content_view - отображение контента страниц;
     * @param mixed $data - массив, содержащий элементы контента страницы;
     */
    function generate($content_view, $data = null)
    {
        /*
          if(is_array($data)) {
          // преобразуем элементы массива в переменные
          extract($data);
          }
         */

        include(ROOT . '/views/' . $this->template_view);
    }

}
