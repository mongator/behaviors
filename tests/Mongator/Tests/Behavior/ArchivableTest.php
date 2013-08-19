<?php

/*
 * This file is part of Mongator.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mondongo\Tests\Behavior;

use Mongator\Tests\TestCase;

class ArchivableTest extends TestCase
{
    public function testArchivable()
    {
        $model = $this->mongator->create('Model\Archivable')
            ->setTitle('foo')
            ->save();

        $archive = $model->archive();

        $this->assertInstanceOf('Model\Archivable\Archive', $archive);
        $this->assertFalse($archive->isNew());
        $this->assertEquals($model->getId(), $archive->getDocument());
        $this->assertNotNull($archive->getArchived());
        $this->assertSame('foo', $archive->getTitle());

        $repository = $model->getRepository();
        $archiveRepository = $this->mongator->getRepository('Model\Archivable\Archive');

        $this->assertSame(1, $repository->count());
        $this->assertSame(1, $archiveRepository->count());
    }

    public function testArchivableReference()
    {
        $article = $this->mongator->create('Model\Article')
            ->setTitle('foo')
            ->save();

        $model = $this->mongator->create('Model\ArchivableReference')
            ->setArticle($article)
            ->save();

        $archive = $model->archive();

        $repository = $model->getRepository();
        $archiveRepository = $this->mongator->getRepository('Model\ArchivableReference\Archive');

        $this->assertSame(1, $repository->count());
        $this->assertSame(1, $archiveRepository->count());
    }

    public function testArchiveInsert()
    {
        $model = $this->mongator->create('Model\ArchivableInsert')
            ->setTitle('foo')
            ->save();

        $repository = $model->getRepository();
        $archiveRepository = $this->mongator->getRepository('Model\ArchivableInsert\Archive');

        $this->assertSame(1, $repository->count());
        $this->assertSame(1, $archiveRepository->count());

        $archive = $archiveRepository->createQuery()->one();

        $this->assertSame('foo', $archive->getTitle());
    }

    public function testArchiveUpdate()
    {
        $model = $this->mongator->create('Model\ArchivableUpdate')
            ->setTitle('foo')
            ->save();

        $repository = $model->getRepository();
        $archiveRepository = $this->mongator->getRepository('Model\ArchivableUpdate\Archive');

        $this->assertSame(1, $repository->count());
        $this->assertSame(0, $archiveRepository->count());

        $model->setTitle('bar')->save();

        $this->assertSame(1, $repository->count());
        $this->assertSame(1, $archiveRepository->count());

        $archive = $archiveRepository->createQuery()->one();

        $this->assertSame('bar', $archive->getTitle());
    }

    public function testArchiveDelete()
    {
        $model = $this->mongator->create('Model\ArchivableDelete')
            ->setTitle('foo')
            ->save();

        $repository = $model->getRepository();
        $archiveRepository = $this->mongator->getRepository('Model\ArchivableDelete\Archive');

        $this->assertSame(1, $repository->count());
        $this->assertSame(0, $archiveRepository->count());

        $model->delete();

        $this->assertSame(0, $repository->count());
        $this->assertSame(1, $archiveRepository->count());

        $archive = $archiveRepository->createQuery()->one();

        $this->assertSame('foo', $archive->getTitle());
    }
}
