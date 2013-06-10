<?php

/*
 * This file is part of Mongator.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mondongo\Tests\Behavior;

use Mongator\Tests\TestCase;

class AutoIncrementableTest extends TestCase
{

    public function testCounterDefault()
    {
        $this->assertTrue(class_exists('Model\Counter'));
    }

    public function testCounterCustom()
    {
        $this->assertTrue(class_exists('Model\OtherCounter'));
        $this->assertSame('alt', $this->mongator->getRepository('Model\OtherCounter')->getConnectionName());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testArchivableUninitialized()
    {
        if ( !method_exists('MongoCollection', 'findAndModify') ) {
            $this->markTestSkipped('MongoDB driver >1.3.0 required');
        }

        $model = $this->mongator->create('Model\AutoIncrementable')
            ->setTitle('foo')
            ->save();
    }

    public function testArchivableCustom()
    {
        if ( !method_exists('MongoCollection', 'findAndModify') ) {
            $this->markTestSkipped('MongoDB driver >1.3.0 required');
        }

        $counterRepository = $this->mongator->getRepository('Model\OtherCounter');
        $counterRepository->setSequence('test2', 1);

        $model = $this->mongator->create('Model\AutoIncrementableCustom')
            ->setTitle('foo')
            ->save();

        $this->assertSame(2, $model->getTest());
    }

    public function testSetSequence()
    {
        if ( !method_exists('MongoCollection', 'findAndModify') ) {
            $this->markTestSkipped('MongoDB driver >1.3.0 required');
        }

        $repository = $this->mongator->getRepository('Model\AutoIncrementableCustom');
        $repository->setSequence(2);

        $model = $this->mongator->create('Model\AutoIncrementableCustom')
            ->setTitle('foo')
            ->save();

        $this->assertSame(3, $model->getTest());
    }
}


