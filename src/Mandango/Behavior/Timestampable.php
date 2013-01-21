<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Behavior;

use Mandango\Mondator\ClassExtension;
use Mandango\Mondator\Definition\Method;

/**
 * Timestampable.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class Timestampable extends ClassExtension
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->addOptions(array(
            'createdEnabled' => true,
            'createdField'   => 'createdAt',
            'updatedEnabled' => true,
            'updatedField'   => 'updatedAt',
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function doConfigClassProcess()
    {
        // created
        if ($this->getOption('createdEnabled')) {
            if ( !isset($this->configClass['fields'][$this->getOption('createdField')]) ) {
                $this->configClass['fields'][$this->getOption('createdField')] = 'date';
            }
            $this->configClass['events']['preInsert'][] = 'updateTimestampableCreated';
        }

        // updated
        if ($this->getOption('updatedEnabled')) {
            if ( !isset($this->configClass['fields'][$this->getOption('updatedField')]) ) {
                $this->configClass['fields'][$this->getOption('updatedField')] = 'date';
            }
            $this->configClass['events']['preUpdate'][] = 'updateTimestampableUpdated';
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doClassProcess()
    {
        // created
        if ($this->getOption('createdEnabled')) {
            $fieldSetter = 'set'.ucfirst($this->getOption('createdField'));
            $fieldGetter = 'get'.ucfirst($this->getOption('createdField'));

            $method = new Method('protected', 'updateTimestampableCreated', '', <<<EOF
        if ( \$this->$fieldGetter() !== null ) return true;
        \$this->$fieldSetter(new \DateTime());
EOF
            );
            $this->definitions['document_base']->addMethod($method);
        }

        // updated
        if ($this->getOption('updatedEnabled')) {
            $fieldSetter = 'set'.ucfirst($this->getOption('updatedField'));
            $fieldGetter = 'get'.ucfirst($this->getOption('updatedField'));

            $method = new Method('protected', 'updateTimestampableUpdated', '', <<<EOF
        if ( \$this->$fieldGetter() !== null ) return true;
        \$this->$fieldSetter(new \DateTime());
EOF
            );
            $this->definitions['document_base']->addMethod($method);
        }
    }
}
