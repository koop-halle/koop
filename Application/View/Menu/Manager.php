<?php

namespace Application\View\Menu;

/**
 * Class Manager
 *
 * @package Application\View\Menu
 */
class Manager
{
    const CONTAINER_WEIGHT_CMS        = 100;

    const CONTAINER_WEIGHT_COMMON     = 0;

    const CONTAINER_WEIGHT_IMAGES     = 50;

    const CONTAINER_WEIGHT_PLAYER     = 30;

    const CONTAINER_WEIGHT_TEAM       = 20;

    const CONTAINER_WEIGHT_TOURNAMENT = 10;

    /**
     * @var \Application\View\Menu\Manager
     */
    protected static $instance;

    /**
     * @var \Application\View\Menu\Container[]
     */
    protected $containers;


    /**
     * Manager constructor.
     */
    private function __construct()
    {
        $this->setDefaults();
    }


    /**
     * @return $this
     */
    protected function setDefaults()
    {
        $this
            ->clearItems()
            ->buildCommonMenu()
        ;

        return $this;
    }

    /**
     * @param \Application\View\Menu\Container $item
     *
     * @return $this
     */
    public function addItem(\Application\View\Menu\Container $item)
    {
        $this->containers[] = $item->validate();

        return $this;
    }


    /**
     * @return $this
     */
    protected function buildCommonMenu()
    {
        $currentUser = \Application\Util\DependencyContainer::getInstance()
                                                            ->getApplication()
                                                            ->getCurrentUser()
        ;
        $commonMenu  = \Application\View\Menu\Container::Factory()
                                                       ->setTitle('Common Menu')
                                                       ->setName('commonMenu')
                                                       ->setWeight(self::CONTAINER_WEIGHT_COMMON)
                                                       ->setInline(false)
        ;
        $commonMenu
            ->addItem(
                \Application\View\Menu\Item::Factory()
                                           ->setText('Home')
                                           ->setHref(\Application\Router::buildHome())
                                           ->setRoute(\Application\Router::ROUTE_HOME)
                                           ->setIcon('fa fa-home')
                                           ->setWeight(0)
            )
        ;

        $this->addItem($commonMenu);

        return $this;
    }


    /**
     * @return $this
     */
    public function clearItems()
    {
        $this->containers = [];

        return $this;
    }


    /**
     * @return \Application\View\Menu\Manager
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    /**
     * @return \Application\View\Menu\Container[]
     */
    public function getItems()
    {

        usort($this->containers, function ($left, $right) {
            /**
             * @var \Application\View\Menu\Container $left
             * @var \Application\View\Menu\Container $right
             */
            if ($left->getWeight() > $right->getWeight()) {
                return 1;
            }

            return -1;
        });

        return $this->containers;
    }


    /**
     * @param \Application\View\Menu\Container[] $items
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
     * @param \Application\View\Menu\Container[] $items
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
}

