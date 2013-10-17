<?php

/*
 * This file is part of Mongator.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mongator\Behavior;

use Mongator\Twig\Mongator as MongatorTwig;
use Mandango\Mondator\ClassExtension;
use Mandango\Mondator\Definition\Method;

/**
 * Sluggable.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 * @author Máximo Cuadros <maximo@yunait.com>
 */
class Sluggable extends ClassExtension
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->addRequiredOption('fromField');

        $this->addOptions(array(
            'slugField' => 'slug',
            'unique'    => true,
            'update'    => false,
            'builder'   => array('\Mongator\Behavior\Util\SluggableUtil', 'slugify'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function doConfigClassProcess()
    {
        // field
        if ( !isset($this->configClass['fields'][$this->getOption('slugField')]) ) {
            $this->configClass['fields'][$this->getOption('slugField')] = 'string';
        }

        // index
        if ($this->getOption('unique')) {
            $this->configClass['indexes'][] = array(
                'keys'    => array($this->getOption('slugField') => 1),
                'options' => array('unique' => 1),
            );
        }

        // event
        $this->configClass['events']['preInsert'][] = 'updateSluggableSlug';
        if ($this->getOption('update')) {
            $this->configClass['events']['preUpdate'][] = 'updateSluggableSlug';
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doClassProcess()
    {
        // field
        $slugField = $this->getOption('slugField');

        $this->processTemplate($this->definitions['document_base'],
            file_get_contents(__DIR__.'/templates/SluggableDocument.php.twig')
        );

        // repository ->findOneBySlug()
        $method = new Method('public', 'findOneBySlug', '$slug', <<<EOF

        return \$this->createQuery(array('$slugField' => \$slug))->one();
EOF
        );
        $method->setDocComment(<<<EOF
    /**
     * Returns a document by slug.
     *
     * @param string \$slug The slug.
     *
     * @return mixed The document or null if it does not exist.
     */
EOF
        );
        $this->definitions['repository_base']->addMethod($method);
    }

    protected function configureTwig(\Twig_Environment $twig)
    {
        $twig->addExtension(new MongatorTwig());
    }
}
