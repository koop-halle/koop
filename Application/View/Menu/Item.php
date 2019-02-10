<?php

namespace Application\View\Menu;

/**
 * Class Item
 *
 * @package Application\View\Menu
 */
class Item
{
    /**
     * @var string
     */
    protected $route;

    /**
     * @var string
     */
    protected $href;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var int
     */
    protected $weight = 0;


    /**
     * @return \Application\View\Menu\Item
     */
    public static function Factory()
    {
        return new static();
    }


    /**
     * @return $this
     */
    public function validate()
    {
        if (null === $this->getHref()) {
            throw new \InvalidArgumentException('missing href');
        }
        if (null === $this->getText()) {
            throw new \InvalidArgumentException('missing text');
        }
        if (null === $this->getRoute()) {
            throw new \InvalidArgumentException('missing route');
        }
        if (null === $this->getIcon()) {
            throw new \InvalidArgumentException('missing icon');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }


    /**
     * @param string $href
     *
     * @return $this
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }


    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }


    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }


    /**
     * @param string $route
     *
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }


    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }


    /**
     * @param string $icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }


    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }


    /**
     * @param int $weight
     *
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }
}
