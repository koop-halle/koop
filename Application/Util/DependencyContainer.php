<?php

namespace Application\Util;

/**
 * Class DependencyContainer
 *
 * @package Application\Util
 */
class DependencyContainer
{
    /**
     * @var \Application\Util\DependencyContainer
     */
    protected static $instance;

    /**
     * @var \DI\Container
     */
    protected $container;


    /**
     * DependencyContainer constructor.
     *
     * @throws \Exception
     */
    private function __construct()
    {
        $this
            ->setContainer(
                (new \DI\ContainerBuilder())
                    ->useAutowiring(false)
                    ->useAnnotations(false)
                    ->ignorePhpDocErrors(true)
                    ->build()
            )
            ->init()
        ;
    }


    /**
     * @return $this
     */
    private function init()
    {
        /**
         * @var \Doctrine\ORM\EntityManager $entityManager
         */
        $container = $this->getContainer();
        $container->set(\Application\Configuration::class, new \Application\Configuration(ROOT_DIR . DIRECTORY_SEPARATOR . 'applicationConfiguration.php'));
        $logger = new \Monolog\Logger('logger');
        $logger
            ->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_DIR . DIRECTORY_SEPARATOR . 'app.log', \Monolog\Logger::ALERT))
            ->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_DIR . DIRECTORY_SEPARATOR . 'app.log', \Monolog\Logger::API))
            ->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_DIR . DIRECTORY_SEPARATOR . 'app.log', \Monolog\Logger::CRITICAL))
            ->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_DIR . DIRECTORY_SEPARATOR . 'app.log', \Monolog\Logger::EMERGENCY))
        ;
        if ($this->getConfiguration()->isDevelopment()) {
            $logger
                ->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_DIR . DIRECTORY_SEPARATOR . 'app.log', \Monolog\Logger::DEBUG))
                ->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_DIR . DIRECTORY_SEPARATOR . 'app.log', \Monolog\Logger::INFO))
            ;
        }

        $container->set(\Monolog\Logger::class, $logger);
        require ROOT_DIR . DIRECTORY_SEPARATOR . 'doctrineBootstrap.php';
        $container->set(\Doctrine\ORM\EntityManager::class, $entityManager);
        $container->set(\Application\Manager::class, new \Application\Manager());
        $container->set(\Application\Translator::class, new \Application\Translator());
        $blade = new \Jenssegers\Blade\Blade([ROOT_DIR . DIRECTORY_SEPARATOR . 'views'], ROOT_DIR.DIRECTORY_SEPARATOR.'cache');
        $blade
            ->compiler()
            ->directive(
                'datetime',
                function ($expression) {
                    return "<?php echo with({$expression})->format('F d, Y g:i a'); ?>";
                })
        ;
        $container->set('renderer', $blade);
        $loader = new \Twig\Loader\ArrayLoader();
        $loader->setTemplate('foo', 'bar');
        $loader->setTemplate('bar', 'foo');
        $loader = new \Twig\Loader\FilesystemLoader(ROOT_DIR . DIRECTORY_SEPARATOR . 'twig');
        $twig   = new \Twig\Environment($loader, [
            'cache' => ROOT_DIR . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'twig',
            'debug' => true,
        ]);
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        $twig->addExtension(new \Twig_Extensions_Extension_Text());
        $twig->addExtension(new \Application\Twig\Extension\ConfigurationExtension());
        $twig->addExtension(new \Application\Twig\Extension\TranslatorExtension());
        $twig->addExtension(new \Application\Twig\Extension\YearExtension());
        $twig->addExtension(new \Application\Twig\Extension\LinkBuilderExtension());
        $twig->addExtension(new \Application\Twig\Extension\LogoExtension());
        $twig->addExtension(new \Application\Twig\Extension\DateExtension());
        $twig->addExtension(new \Application\Twig\Extension\TimeExtension());
        $twig->addExtension(new \Application\Twig\Extension\DateTimeExtension());
        $container->set('twig', $twig);
        $container->set(Language::class, new Language());
        $language    = $this
            ->getLanguageSession()
            ->getFromSession()
        ;
        $currentUser = $this
            ->getApplication()
            ->getCurrentUser()
        ;
        if (null !== $currentUser) {
            $language = $currentUser->getLanguage();
        }
        $this
            ->getTranslator()
            ->setLanguage($language)
        ;

        return $this;
    }


    /**
     * @return \DI\Container
     */
    private function getContainer()
    {
        return $this->container;
    }


    /**
     * @param \DI\Container $container
     *
     * @return $this
     */
    private function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }


    /**
     * @return \Application\Util\DependencyContainer
     */
    public static function getInstance()
    {

        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    /**
     * @return \Twig\Environment
     */
    public function getTwig()
    {
        return $this->getContainer()->get('twig');
    }


    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get(\Doctrine\ORM\EntityManager::class);
    }


    /**
     * @return \Application\Manager
     */
    public function getApplication()
    {
        return $this->getContainer()->get(\Application\Manager::class);
    }



    /**
     * @return \Jenssegers\Blade\Blade
     */
    public function getRenderer()
    {
        return $this->getContainer()->get('renderer');
    }


    /**
     * @return \Application\Translator
     */
    public function getTranslator()
    {
        return $this->getContainer()->get(\Application\Translator::class);
    }


    /**
     * @return \Monolog\Logger
     */
    public function getLogger()
    {
        return $this->getContainer()->get(\Monolog\Logger::class);
    }


    /**
     * @return \Application\Configuration
     */
    public function getConfiguration()
    {
        return $this->getContainer()->get(\Application\Configuration::class);
    }


    /**
     * @return \Application\Util\Language
     */
    public function getLanguageSession()
    {
        return $this->getContainer()->get(\Application\Util\Language::class);
    }
}