<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'doctrineBootstrap.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);