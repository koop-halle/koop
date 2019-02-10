<?php

namespace Application\Util;

/**
 * Class Password
 *
 * @package Application\Util
 */
class Password
{
    /**
     * @param string $password
     *
     * @return string
     */
    public static function hash($password)
    {
        return sha1(sprintf('____%s____', md5($password)));
    }
}