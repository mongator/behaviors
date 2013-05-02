<?php

/*
 * This file is part of Mandango.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mondongo\Tests\Behavior;

use Mandango\Tests\TestCase;

class HashableTest extends TestCase
{
    public function testHashable()
    {
        $document = $this->mandango->create('Model\Hashable');
        $document->setTitle('foo');
        $document->setContent('bar');
        $document->save();

        $this->assertNotNull($document->getHash());
        $this->assertSame('2fdffc72acac41933e3e4667cd6d388d', $document->getHash());
    }

    public function testHashableFields()
    {
        $document = $this->mandango->create('Model\HashableFields');
        $document->setTitle('foo');
        $document->setContent('bar');
        $document->save();

        $this->assertNotNull($document->getHash());
        $this->assertSame('4109c93b3462dad44dc7bc4215c9f174', $document->getHash());
    }

    public function testHashableReferences()
    {
        $one = $this->mandango->create('Model\Hashable');
        $one->setTitle('qux');

        $many1 = $this->mandango->create('Model\Hashable');
        $many1->setTitle('bar');

        $many2 = $this->mandango->create('Model\Hashable');
        $many2->setTitle('quz');

        $document = $this->mandango->create('Model\HashableReferences');
        $document->setTitle('foo');
        $document->setContent('bar');
        $document->setRefOne($one);
        $document->addRefMany($many2);
        $document->addRefMany($many1);

        $document->save();

        $many = array($many1->getId(), $many2->getId());
        sort($many);

        $values = array(
            'foo',
            'bar',
            $one->getId(),
            $many
        );

        $this->assertNotNull($document->getHash());
        $this->assertSame(md5(serialize($values)), $document->getHash());
    }

    public function testHashableEmbedded()
    {
        $one = $this->mandango->create('Model\Comment');
        $one->setTitle('qux');

        $many1 = $this->mandango->create('Model\Comment');
        $many1->setTitle('bar');
        $many1->setContent('baz');

        $many2 = $this->mandango->create('Model\Comment');
        $many2->setContent('bar');
        $many2->setTitle('quz');

        $document = $this->mandango->create('Model\HashableEmbedded');
        $document->setTitle('foo');
        $document->setContent('bar');
        $document->setEmbOne($one);
        $document->addEmbMany($many2);
        $document->addEmbMany($many1);

        $document->save();

        $values = array(
            'foo',
            'bar',
            array('content' => null, 'title' => 'qux'),
            array('content' => 'bar', 'title' => 'quz'),
            array('content' => 'baz', 'title' => 'bar'),
        );

        $this->assertNotNull($document->getHash());
        $this->assertSame(md5(serialize($values)), $document->getHash());
    }

    public function testHashableConfigured()
    {
        $one = $this->mandango->create('Model\Comment');
        $one->setTitle('qux');

        $many1 = $this->mandango->create('Model\Comment');
        $many1->setTitle('bar');
        $many1->setContent('baz');

        $many2 = $this->mandango->create('Model\Comment');
        $many2->setContent('bar');
        $many2->setTitle('quz');

        $rone = $this->mandango->create('Model\Hashable');
        $rone->setTitle('qux');

        $rmany1 = $this->mandango->create('Model\Hashable');
        $rmany1->setTitle('bar');

        $rmany2 = $this->mandango->create('Model\Hashable');
        $rmany2->setTitle('quz');

        $document = $this->mandango->create('Model\HashableConfigured');
        $document->setTitle('foo');
        $document->setContent('bar');
        $document->setEmbOne($one);
        $document->addEmbMany($many2);
        $document->addEmbMany($many1);
        $document->setRefOne($rone);
        $document->addRefMany($rmany2);
        $document->addRefMany($rmany1);

        $document->save();

        $values = array(
            'foo',
            $rone->getId(),
            array('content' => 'bar', 'title' => 'quz'),
            array('content' => 'baz', 'title' => 'bar'),
        );

        $this->assertNotNull($document->getHash());
        $this->assertSame(md5(serialize($values)), $document->getHash());
    }
}
