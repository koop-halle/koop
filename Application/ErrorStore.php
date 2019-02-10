<?php

namespace Application;

/**
 * Class ErrorStore
 *
 * @package Application
 */
class ErrorStore implements \AdamWathan\Form\ErrorStore\ErrorStoreInterface
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;


    /**
     * OldInputProvider constructor.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array                                     $fields
     */
    private function __construct(\Symfony\Component\HttpFoundation\Request $request, array $fields = [])
    {
        $this
            ->setRequest($request)
            ->setFields($fields)
            ->calculate()
        ;
    }


    /**
     * @return $this
     */
    protected function calculate()
    {
        return $this;
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array                                     $fields
     *
     * @return \Application\ErrorStore
     */
    public static function Factory(\Symfony\Component\HttpFoundation\Request $request, array $fields = [])
    {
        return new static($request, $fields);
    }


    /**
     * @param $key
     * @param $error
     *
     * @return $this
     */
    public function addError($key, $error)
    {
        $errors       = $this->getErrors();
        $errors[$key] = $error;
        $this->setErrors($errors);

        return $this;
    }


    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


    /**
     * @param array $errors
     *
     * @return $this
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }


    /**
     * @param string $key
     *
     * @return string
     */
    public function getError($key)
    {
        return $this->getErrors()[$key];
    }


    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasError($key)
    {
        return true === array_key_exists($key, $this->getErrors());
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return $this
     */
    protected function setRequest(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request = $request;

        return $this;
    }


    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }


    /**
     * @param array $fields
     *
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }
}