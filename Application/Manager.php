<?php

namespace Application;

/**
 * Class Manager
 *
 * @package Application
 */
class Manager
{
    use ManagerBaseTrait;

    const SESSION_KEY_USER   = 'user';

    const SESSION_KEY_BEACON = 'beacon';
}
