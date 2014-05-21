<?php

/*
 * This file is part of Mongator.
 *
 * (c) Máximo Cuadros <mcuadros@gmail.com>
 * (c) Eduardo Gulias <me@egulias.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mondongo\Tests\Behavior;

use Mongator\Tests\TestCase;

class EmbeddedUniquableTest extends TestCase
{
    public function testRemoveDuplicates()
    {
        $one = $this->mongator->create('Model\Comment');
        $one->setTitle('foo');

        $two = $this->mongator->create('Model\Comment');
        $two->setTitle('foo');

        $document = $this->mongator->create('Model\EmbeddedUniquable');
        $document->addFoo($one);
        $document->addFoo($two);
        $document->save();

        $this->assertCount(1, $document->getFoo());
    }

    public function testNormalBehavior()
    {
        $one = $this->mongator->create('Model\Comment');
        $one->setTitle('bar');

        $two = $this->mongator->create('Model\Comment');
        $two->setTitle('foo');

        $document = $this->mongator->create('Model\EmbeddedUniquable');
        $document->addFoo($one);
        $document->addFoo($two);
        $document->save();

        $this->assertCount(2, $document->getFoo());
    }
}
