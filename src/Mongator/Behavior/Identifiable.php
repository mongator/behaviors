<?php

/*
 * This file is part of Mongator.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mongator\Behavior;

use Mandango\Mondator\ClassExtension;
use Mandango\Mondator\Definition\Method;

/**
 * Identifiable.
 *
 * @author Máximo Cuadros <maximo@yunait.com>
 */
class Identifiable extends ClassExtension
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->addOptions(array(
            'idField'   => 'id',
            'idGeneratorMethod' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function doConfigClassProcess()
    {
        // created
        if ($this->getOption('idField')) {
            $this->configClass['fields'][$this->getOption('idField')] = 'raw';
            $this->configClass['events']['preInsert'][] = 'setIdToIdentifiableDocument';
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doClassProcess()
    {
        // created
        if ($this->getOption('idField')) {
            $fieldSetter = 'set'.ucfirst($this->getOption('idField'));
            $idGeneratorCode = $this->getIdGeneratorFunction();

            $method = new Method('protected', 'setIdToIdentifiableDocument', '', <<<EOF
        \$this->$fieldSetter($idGeneratorCode);
EOF
            );
            $this->definitions['document_base']->addMethod($method);
        }

    }

    private function getIdGeneratorFunction()
    {
        if ($this->getOption('idGeneratorMethod')) {
            $idGeneratorMethod = var_export($this->getOption('idGeneratorMethod'), true);

            return 'call_user_func(' . $idGeneratorMethod .', $this)';
        } else {
            return 'new \MongoId()';
        }
    }
}
