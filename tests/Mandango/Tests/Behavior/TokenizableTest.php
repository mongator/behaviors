<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mondongo\Tests\Behavior;

use Mandango\Tests\TestCase;

class TokenizableTest extends TestCase
{
    public function testTokenizable()
    {
        $document = $this->mandango->create('Model\Tokenizable');
        $document->setField('foo');
        $document->save();

        $this->assertNotNull($document->getToken());
        $this->assertInternalType('string', $document->getToken());
    }

    public function testRepositoryFindOneByToken()
    {
        $repository = $this->mandango->getRepository('Model\Tokenizable');

        $documents = array();
        for ($i = 0; $i < 9; $i++) {
            $documents[] = $document = $this->mandango->create('Model\Tokenizable');
            $document->setField('foo'.$i);
        }
        $repository->save($documents);

        $this->assertSame($documents[3], $repository->findByToken($documents[3]->getToken()));
        $this->assertSame($documents[6], $repository->findByToken($documents[6]->getToken()));
    }

    public function testField()
    {
        $document = $this->mandango->create('Model\TokenizableField');
        $document->setField('foo');
        $document->save();

        $this->assertNotNull($document->getAnotherField());
        $this->assertInternalType('string', $document->getAnotherField());
    }

    public function testLength()
    {
        $document = $this->mandango->create('Model\TokenizableLength');
        $document->setField('foo');
        $document->save();

        $this->assertSame(5, strlen($document->getToken()));
    }
}
