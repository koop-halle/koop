<?php

namespace Application\View\Menu;

/**
 * Class Container
 *
 * @package Application\View\Menu
 */
class Container
{
    /**
     * @var \Application\View\Menu\Item[]
     */
    protected $items;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $weight = 0;

    /**
     * @var bool
     */
    protected $inline = false;


    /**
     * @return \Application\View\Menu\Container
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
        if (null === $this->getName()) {
            throw new \InvalidArgumentException('missing name');
        }
        if (null === $this->getTitle()) {
            throw new \InvalidArgumentException('missing title');
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }


    /**
     * @return \Application\View\Menu\Item[]
     */
    public function getItems()
    {
        usort($this->items, function ($a, $b) {
            /**
             * @var \Application\View\Menu\Item $a
             * @var \Application\View\Menu\Item $b
             */
            if ($a->getWeight() > $b->getWeight()) {
                return 1;
            }

            return -1;
        });

        return $this->items;
    }


    /**
     * @param \Application\View\Menu\Item[] $items
     *
     * @return $this
     */
    public function setItems(array $items)
    {
        return $this
            ->clearItems()
            ->addItems($items)
            ;
    }


    /**
     * @param \Application\View\Menu\Item[] $items
     *
     * @return $this
     */
    public function addItems(array $items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }

        return $this;
    }


    /**
     * @param \Application\View\Menu\Item $item
     *
     * @return $this
     */
    public function addItem(\Application\View\Menu\Item $item)
    {
        $this->items[] = $item->validate();

        return $this;
    }


    /**
     * @return $this
     */
    public function clearItems()
    {
        $this->items = [];

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


    /**
     * @return bool
     */
    public function isInline()
    {
        return $this->inline;
    }


    /**
     * @param bool $inline
     *
     * @return $this
     */
    public function setInline($inline)
    {
        $this->inline = $inline;

        return $this;
    }
}
