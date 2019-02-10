<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$isDevMode = true;
$config    = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    [__DIR__ . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Doctrine' . DIRECTORY_SEPARATOR . 'Model'],
    $isDevMode
);
$conn      = [
    'driver'        => 'pdo_mysqli',
    'path'          => __DIR__ . '/db.sqlite',
    'charset'       => 'utf8',
    'driverOptions' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ],
];
$dbParams  = require __DIR__ . DIRECTORY_SEPARATOR . 'doctrineConfig.php';
$loader    = require __DIR__ . '/vendor/autoload.php';
$loader->add('Gedmo', __DIR__ . '/vendor/gedmo/doctrine-extensions/lib');
$loader->add('Entity', __DIR__ . '/Application');
Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
    __DIR__ . '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
);
$cache                  = new Doctrine\Common\Cache\ArrayCache();
$annotationReader       = new Doctrine\Common\Annotations\SimpleAnnotationReader();
$cachedAnnotationReader = new Doctrine\Common\Annotations\CachedReader(
    $annotationReader, // use reader
    $cache // and a cache driver
);
$driverChain            = new Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();
Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(
    $driverChain, // our metadata driver chain, to hook into
    $cachedAnnotationReader // our cached annotation reader
);
$evm = new \Doctrine\Common\EventManager();
$timestampableListener = new Gedmo\Timestampable\TimestampableListener();
$timestampableListener->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($timestampableListener);
$blameableListener = new \Gedmo\Blameable\BlameableListener();
$blameableListener->setAnnotationReader($cachedAnnotationReader);
$blameableListener->setUserValue('MyUsername');
$evm->addEventSubscriber(new $blameableListener);
$evm->addEventSubscriber(new \Gedmo\Sluggable\SluggableListener());
$evm->addEventSubscriber(new \Gedmo\Tree\TreeListener());
$translationListener = new \Gedmo\Translatable\TranslatableListener();
$translationListener->setTranslatableLocale('de_de');
$evm->addEventSubscriber($translationListener);
try {
    $entityManager = \Doctrine\ORM\EntityManager::create($dbParams, $config);
    /*
     * overwrite entity's table names to get prefixed stuff alive
     */

    $dynamicTables = [
        \Application\Doctrine\Model\User::class => \Application\Doctrine\Model\User::TABLE_NAME,
    ];
    foreach ($dynamicTables as $model => $tableName) {
        $entityManager
            ->getClassMetadata($model)
            ->setPrimaryTable(['name' => $dbParams['table_prefix'] . $tableName])
        ;
    }
} catch (\Exception $exception) {
    die('unable to setup orm');
}
