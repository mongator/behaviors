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

use Mongator\Twig\Mongator as MongatorTwig;
use Mandango\Mondator\ClassExtension;
use Mandango\Mondator\Definition\Method;
use InvalidArgumentException;

/**
 * Human readable field.
 *
 * @author Máximo Cuadros <maximo@yunait.com>
 */
class Stringifiable extends ClassExtension
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->addRequiredOption('fromFields');
        $this->addOption('format');
    }

    /**
     * {@inheritdoc}
     */
    protected function doConfigClassProcess()
    {
        $this->validateFromFields();
        $this->setDefaultFormat();
    }

    private function validateFromFields()
    {
        if (!is_array($this->getOption('fromFields'))) {
            throw new InvalidArgumentException(
                'The option "fromFields" should be an array.'
            );
        }

        $fromFields = array();
        foreach ($this->getOption('fromFields') as $key => $value) {
            if (is_numeric($key)) {
                $fromFields[$value] = 1;
            } else {
                $fromFields[$key] = $value;
            }
        }
        
        if ( count($fromFields) != 0 ) {
            $unknownFields = array_diff(
                array_keys($fromFields),
                array_merge(
                    array_keys($this->configClass['fields']),
                    array_keys($this->configClass['embeddedsOne']),
                    array_keys($this->configClass['referencesOne'])
                )
            );

            if (count($unknownFields) != 0) {
                throw new InvalidArgumentException(sprintf(
                    'Unknown fields "%s" in option "fromFields".',
                    implode(', ', $unknownFields)
                ));
            }

            $this->setOption('fromFields', $fromFields);
        }
    }

    private function setDefaultFormat()
    {
        if (!$this->getOption('format')) {
            $fieldsCount = count($this->getOption('fromFields'));
            $this->setOption('format', str_repeat('%s ', $fieldsCount));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doClassProcess()
    {
        $this->processTemplate($this->definitions['document_base'],
            file_get_contents(__DIR__.'/templates/StringifiableDocument.php.twig')
        );
    }

    protected function configureTwig(\Twig_Environment $twig)
    {
        $twig->addExtension(new MongatorTwig());
    }
}
