<?php

/*
 * This file is part of Mongator.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mongator\Tests;

use Mongator\Connection;
use Mongator\Mongator;
use Mongator\Archive;
use Mongator\Type\Container as TypeContainer;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected static $staticConnection;
    protected static $staticMongator;

    protected $metadataFactoryClass = 'Model\Mapping\MetadataFactory';
    protected $server = 'mongodb://localhost:27017';
    protected $dbName = 'mongator_behaviors_tests';

    protected $connection;
    protected $mongator;
    protected $unitOfWork;
    protected $metadataFactory;
    protected $cache;
    protected $mongo;
    protected $db;

    protected function setUp()
    {
        if (!static::$staticConnection) {
            static::$staticConnection = new Connection($this->server, $this->dbName);
        }
        $this->connection = static::$staticConnection;

        if (!static::$staticMongator) {
            static::$staticMongator = new Mongator(new $this->metadataFactoryClass, function($log) {});
            static::$staticMongator->setConnection('default', $this->connection);

            static::$staticMongator->setConnection('alt', $this->connection);
            static::$staticMongator->setDefaultConnectionName('default');
        }
        $this->mongator = static::$staticMongator;
        $this->unitOfWork = $this->mongator->getUnitOfWork();
        $this->metadataFactory = $this->mongator->getMetadataFactory();
        $this->cache = $this->mongator->getFieldsCache();

        foreach ($this->mongator->getAllRepositories() as $repository) {
            $repository->getIdentityMap()->clear();
        }

        $this->mongo = $this->connection->getMongo();
        $this->db = $this->connection->getMongoDB();

        foreach ($this->db->listCollections() as $collection) {
            $collection->drop();
        }
    }

    protected function tearDown()
    {
        Archive::clear();
        TypeContainer::reset();
    }
}
