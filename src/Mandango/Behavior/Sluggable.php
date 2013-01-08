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
 * Sluggable.
 *
 * @author Pablo Díez <pablodip@gmail.com>
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
            'builder'   => array('\Mandango\Behavior\Util\SluggableUtil', 'slugify'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function doConfigClassProcess()
    {
        // field
        $this->configClass['fields'][$this->getOption('slugField')] = 'string';

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

        // update slug
        $fromField = $this->getOption('fromField');
        $fromFieldCamelized = \Mandango\Twig\Mandango::toCamelCase($fromField);
        $slugFieldCamelized = \Mandango\Twig\Mandango::toCamelCase($slugField);
        $builder = var_export($this->getOption('builder'), true);

        $uniqueCode = '';
        if ($this->getOption('unique')) {
            $uniqueCode = <<<EOF
        \$similarSlugs = array();
        foreach (\$this->getRepository()->getCollection()
            ->find(array('$slugField' => new \MongoRegex('/^'.\$slug.'/')))
        as \$result) {
            \$similarSlugs[] = \$result['$slugField'];
        }

        \$i = 1;
        while (in_array(\$slug, \$similarSlugs)) {
            \$slug = \$proposal.'-'.++\$i;
        }
EOF;
        }

        $method = new Method('protected', 'updateSluggableSlug', '', <<<EOF
        if ( \$this->get$slugFieldCamelized() !== null ) return true;
        \$slug = \$proposal = call_user_func($builder, \$this->get$fromFieldCamelized());

$uniqueCode

        \$this->set$slugFieldCamelized(\$slug);
EOF
        );
        $this->definitions['document_base']->addMethod($method);

        // repository ->findOneBySlug()
        $method = new Method('public', 'findOneBySlug', '$slug', <<<EOF
        if ( !\$slug || strlen(\$slug) == 0 ) throw new Exception('Argumento \$slug invalido.');
        return \$this->mergeCriteria(array('$slugField' => (string)\$slug));
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
        $this->definitions['query_base']->addMethod($method);
    }
}
