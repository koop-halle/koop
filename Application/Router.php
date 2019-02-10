<?php

namespace Application;

/**
 * Class Router
 *
 * @package Application
 */
class Router
{
    const ROUTE_ERROR = '/error';
    const ROUTE_HOME   = '/';


    /**
     * @return string
     */
    public static function buildHome()
    {
        return static::ROUTE_HOME;
    }


    /**
     * @param string $what
     * @param string $with
     * @param string $where
     *
     * @return string
     */
    private static function replace($what, $with, $where)
    {
        return str_replace($what, $with, $where);
    }
}
