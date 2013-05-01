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
 * Tokenizable.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class Tokenizable extends ClassExtension
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->addOptions(array(
            'field'  => 'token',
            'length' => 10
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function doConfigClassProcess()
    {
        $this->configClass['fields'][$this->getOption('field')] = 'string';
        $this->configClass['events']['preInsert'][] = 'updateTokenizableToken';
    }

    /**
     * {@inheritdoc}
     */
    protected function doClassProcess()
    {
        $field = $this->getOption('field');
        $length = $this->getOption('length');

        // field
        $this->configClass['fields'][$field] = array('type' => 'string');

        // index
        $this->configClass['indexes'][] = array('keys' => array($field => 1), array('unique' => 1));

        // event
        $fieldSetter = 'set'.ucfirst($field);

        $method = new Method('public', 'updateTokenizableToken', '', <<<EOF
        do {
            \$token = '';
            for (\$i = 1; \$i <= $length; \$i++) {
                \$token .= substr(sha1(microtime(true).mt_rand(111111, 999999)), mt_rand(0, 39), 1);
            };

            \$result = \$this->getRepository()->getCollection()->findOne(array('$field' => \$token));
        } while (\$result);

        \$this->$fieldSetter(\$token);
EOF
        );
        $this->definitions['document_base']->addMethod($method);

        // repository ->findOneBytoken()
        $method = new Method('public', 'findByToken', '$token', <<<EOF
        return \$this->createQuery(array('$field' => \$token))->one();
EOF
        );
        $method->setDocComment(<<<EOF
    /**
     * Returns a document by token.
     *
     * @param string \$token The token.
     *
     * @return mixed The document or null if it does not exist.
     */
EOF
        );
        $this->definitions['repository_base']->addMethod($method);
    }
}
