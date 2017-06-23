<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__.'/../vendor/autoload.php';

$entityManager = (new \Kioku\Doctrine())->init();

return ConsoleRunner::createHelperSet($entityManager);