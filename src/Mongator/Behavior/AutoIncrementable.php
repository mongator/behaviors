<?php
namespace Mongator\Behavior;

use Mandango\Mondator\ClassExtension;
use Mandango\Mondator\Definition\Method;
use Mongator\Twig\Mongator as MongatorTwig;

/**
 * AutoIncrementable.
 *
 * @author Máximo Cuadros <maximo@yunait.com>
 */
class AutoIncrementable extends ClassExtension
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if ( $this->isCounter() ) return;
        
        $this->addOptions(array(
            'counterName' => null,
            'counterField' => null,
            'collection' => 'counters',
            'counterClass' => 'Model\Counter'
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function doNewConfigClassesProcess()
    {
        if ( $this->isCounter() ) return;
        if ( isset($this->newConfigClasses[$this->getOption('counterClass')]) ) return;
        $this->newConfigClasses[$this->getOption('counterClass')] = $this->getCounterConfigClass();
    }

    /**
     * {@inheritdoc}
     */
    protected function doConfigClassProcess()
    {
        if ( $this->isCounter() ) return;

        if (!$this->getOption('counterField')) {
            throw new \RuntimeException('counterField option cannot be null');
        }

        if (!$this->getOption('counterName')) {
            throw new \RuntimeException('counterName option cannot be null');
        }

        // field
        if ( !isset($this->configClass['fields'][$this->getOption('counterField')]) ) {
            $this->configClass['fields'][$this->getOption('counterField')] = 'integer';
        } 
        
        // index
        $this->configClass['indexes'][] = array(
            'keys'    => array($this->getOption('counterField') => 1),
            'options' => array('unique' => 1),
        );

        // event
        $this->configClass['events']['preInsert'][] = 'updateAutoIncrementableFields';
    }

    /**
     * {@inheritdoc}
     */
    protected function doClassProcess()
    {
        if ( $this->isCounter() ) {
            $this->processTemplate($this->definitions['repository_base'],
                file_get_contents(__DIR__.'/templates/AutoIncrementableCounterRepository.php.twig')
            );

        } else {
            $this->processTemplate($this->definitions['document_base'],
                file_get_contents(__DIR__.'/templates/AutoIncrementableDocument.php.twig')
            );

            $this->processTemplate($this->definitions['repository_base'],
                file_get_contents(__DIR__.'/templates/AutoIncrementableRepository.php.twig')
            );
        }
    }


    private function getCounterConfigClass()
    {
        if ( !isset($this->configClass['connection']) ) $connection = null;
        else $connection = $this->configClass['connection'];
        
        return array(
            'collection' => $this->getOption('collection'),
            'connection' => $connection,
            'counter' => true,
            'fields' =>  array(
                'name' => 'string',
                'sequence' => 'integer',
            ),
            'indexes' => array(
                array(
                    'keys'    => array('name' => 1),
                    'options' => array('unique' => 1),
                )
            ),
            'behaviors' => array(
                array('class' => 'Mongator\Behavior\AutoIncrementable'),
            ),
        );
    }


    private function isCounter()
    {
        return !empty($this->configClass['counter']);
    }

    protected function configureTwig(\Twig_Environment $twig)
    {
        $twig->addExtension(new MongatorTwig());
    }
}