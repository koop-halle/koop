<?php

namespace Application\View;

/**
 * Class PathMap
 *
 * @package Application\View
 */
class PathMap
{
    const _DEFAULT_ACTION_ADD  = 'add';

    const _DEFAULT_ACTION_EDIT = 'edit';

    const _DEFAULT_ACTION_LIST = 'list';

    const _DEFAULT_ACTION_SHOW = 'show';

    const _PATH_AUTH_PREFIX    = self::_PATH_PREFIX . 'auth.';

    const _PATH_CMS_PREFIX     = self::_PATH_PREFIX . 'cms.';

    const _PATH_PREFIX         = 'action' . self::_PATH_SEPARATOR;

    const _PATH_SEPARATOR      = '.';

#########
    const AUTH_LOGIN = self::_PATH_AUTH_PREFIX . 'login';

#########
    const CMS_ERROR = self::_PATH_CMS_PREFIX . 'error';

    const CMS_HOME  = self::_PATH_CMS_PREFIX . 'home';
}