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
 * Hashable.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class Hashable extends ClassExtension
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->addOptions(array(
            'createdEnabled' => true,
            'updatedEnabled' => true,
            'field'  => 'hash',
            'fromFields' => array()
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function doConfigClassProcess()
    {
        $this->configClass['fields'][$this->getOption('field')] = 'string';

        if ($this->getOption('createdEnabled')) {
            $this->configClass['events']['preInsert'][] = 'updateHashableHash';
        }

        if ($this->getOption('updatedEnabled')) {
            $this->configClass['events']['preUpdate'][] = 'updateHashableHash';
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doClassProcess()
    {
        $field = $this->getOption('field');
        $fromFields = $this->getOption('fromFields');
        if ( count($fromFields) == 0 ) {
            $fromFields = array_diff(
                array_merge(
                    array_keys($this->configClass['fields']),
                    array_keys($this->configClass['embeddedsOne']),
                    array_keys($this->configClass['embeddedsMany'])
                ),
                array($field)
            );

            $this->setOption('fromFields', $fromFields);
        }

        // field
        $this->configClass['fields'][$field] = array('type' => 'string');

        // document ->updateHashableHash()
        $this->processTemplate($this->definitions['document_base'],
            file_get_contents(__DIR__.'/templates/HashableDocument.php.twig')
        );

        // repository ->findOneByHash()
        $method = new Method('public', 'findByHash', '$hash', <<<EOF
        return \$this->createQuery(array('$field' => \$hash))->one();
EOF
        );
        $method->setDocComment(<<<EOF
    /**
     * Returns a document by hash.
     *
     * @param string \$hash The hash.
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
