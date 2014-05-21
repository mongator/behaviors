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

use Mandango\Mondator\ClassExtension;
use Mongator\Twig\Mongator as MongatorTwig;

class EmbeddedUniquable extends ClassExtension
{
    protected function setUp()
    {
        $this->addOptions(array(
            'fields' => array(),
        ));
    }

    protected function doClassProcess()
    {
        $this->processTemplate(
            $this->definitions['document_base'],
            file_get_contents(__DIR__.'/templates/EmbeddedUniquable.php.twig')
        );
    }

    protected function doConfigClassProcess()
    {
        foreach ($this->getOption('fields') as $field) {
            $methodName = sprintf('ensure%sUniqueness', ucfirst($field['embeddedsMany']));
            $this->configClass['events']['preInsert'][] = $methodName;
            $this->configClass['events']['preUpdate'][] = $methodName;
        }
    }

    protected function configureTwig(\Twig_Environment $twig)
    {
        $twig->addExtension(new MongatorTwig());
    }
}
