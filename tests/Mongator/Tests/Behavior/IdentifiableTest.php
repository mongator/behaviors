<?php

/*
 * This file is part of Mongator.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mongator\Tests\Behavior;

use Mongator\Tests\TestCase;

class IdentifiableTest extends TestCase
{
    public function testIdentifiable()
    {
        $document = $this->mongator->create('Model\IdentifiableDocument');
        $emb = $this->mongator->create('Model\Identifiable');
        $emb->setTitle('foo');

        $this->assertNull($emb->getId());

        $document->setEmbOne($emb);
        $document->save();

        $this->assertInstanceOf('MongoId', $emb->getId());
    }

    public function testIdentifiableCustom()
    {
        $document = $this->mongator->create('Model\IdentifiableDocument');
        $emb = $this->mongator->create('Model\IdentifiableCustom');
        $emb->setTitle('foo');

        $this->assertNull($emb->getId());

        $document->setEmbTwo($emb);
        $document->save();

        $this->assertSame('foo', $emb->getId());
    }

    public static function generateId(\Model\IdentifiableCustom $document)
    {
        return 'foo';
    }
}
