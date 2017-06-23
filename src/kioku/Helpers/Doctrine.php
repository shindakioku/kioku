<?php

namespace Kioku\Helpers;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Yaml\Yaml;

class Doctrine
{
    /**
     * @var Yaml
     */
    protected $yaml;

    /**
     * Doctrine constructor.
     */
    public function __construct()
    {
        $this->yaml = new Yaml();
    }

    /**
     * @return EntityManager
     */
    public function init(): EntityManager
    {
        $config = Setup::createConfiguration(
            $this->yaml->parse(file_get_contents(__DIR__.'/../../config/database.yaml'))['isDev']
        );

        $driver = new AnnotationDriver(
            new AnnotationReader(), $this->yaml->parse(file_get_contents(__DIR__.'/../../config/database.yaml'))['path']
        );
        AnnotationRegistry::registerLoader('class_exists');
        $config->setMetadataDriverImpl($driver);
        $em = EntityManager::create(
            $this->yaml->parse(file_get_contents(__DIR__.'/../../config/database.yaml'))['settings'], $config
        );

        return $em;
    }

    /**
     * @return EntityManager
     */
    public function em()
    {
        return $this->init();
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        $conn = DriverManager::getConnection(
            $this->yaml->parse(file_get_contents(__DIR__.'/../../config/database.yaml'))['settings']
        );

        return $conn->createQueryBuilder();
    }
}