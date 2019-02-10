<?php

namespace Application\Action\Cms;

/**
 * Class ErrorAction
 *
 * @package Application\Action\Cms
 */
class ErrorAction extends \Application\Action\AbstractAction
{
    use \Application\Action\Cms\Share\CmsId;

    const ROUTE                     = \Application\Router::ROUTE_ERROR;

    const ACL_ALLOWED_FOR_ANONYMOUS = true;

    const ACL_ADMIN_REQUIRED        = false;

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @var int
     */
    protected $code;


    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render(
            \Application\View\PathMap::CMS_ERROR,
            [
                'code'      => $this->getCode(),
                'exception' => $this->getException(),
            ]
        );
    }


    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }


    /**
     * @param \Exception $exception
     *
     * @return $this
     */
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;

        return $this;
    }


    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }


    /**
     * @param int $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}