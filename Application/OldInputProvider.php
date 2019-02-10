<?php

namespace Application;

/**
 * Class OldInputProvider
 *
 * @package Application
 */
class OldInputProvider implements \AdamWathan\Form\OldInput\OldInputInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $data;


    /**
     * OldInputProvider constructor.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    private function __construct(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->setData($request->request->all());
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Application\OldInputProvider
     */
    public static function Factory(\Symfony\Component\HttpFoundation\Request $request)
    {
        return new static($request);
    }


    /**
     * @inheritdoc
     */
    public function getOldInput($key)
    {
        if (false === array_key_exists($key, $this->getData())) {
            return null;
        }

        return $this->getData()[$key];
    }


    /**
     * @inheritdoc
     */
    public function hasOldInput()
    {
        return 0 !== sizeof($this->getData());
    }


    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }


    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }
}