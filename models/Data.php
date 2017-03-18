<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Checks and returns values of POST, GET and COOKIE parameters
 *
 * @author Nikitin Dima
 */
class Data
{

    /**
     * Checks whether set GET parameter
     * 
     * @param string $param
     * @return bool
     */
    public static function isSetGetParameter($param)
    {
        return filter_input(INPUT_GET, $param) != NULL;
    }

    /**
     * Checks whether set POST parameter
     * 
     * @param string $param
     * @return bool
     */
    public static function isSetPostParameter($param)
    {
        return filter_input(INPUT_POST, $param) != NULL;
    }

    /**
     * Checks whether set COOKIE parameter
     * 
     * @param string $param
     * @return bool
     */
    public static function isSetCookieParameter($param)
    {
        return filter_input(INPUT_COOKIE, $param) != NULL;
    }

    /**
     * returns GET parameter by name
     * 
     * @param string $param
     * @return string
     */
    public static function getGetParameter($param)
    {
        return mysql_escape_string(htmlspecialchars(strip_tags(filter_input(INPUT_GET, $param))));
    }

    /**
     * returns POST parameter by name
     * 
     * @param string $param
     * @return string
     */
    public static function getPostParameter($param)
    {
        return mysql_escape_string(htmlspecialchars(strip_tags(filter_input(INPUT_POST, $param))));
    }

    /**
     * returns COOKIE parameter by name
     * 
     * @param string $param
     * @return string
     */
    public static function getCookieParameter($param)
    {
        return mysql_escape_string(htmlspecialchars(strip_tags(filter_input(INPUT_COOKIE, $param))));
    }

}
