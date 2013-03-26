<?php
namespace Mandango\Behavior;

use Mandango\Mondator\ClassExtension;
use Mandango\Mondator\Definition\Method;
use Mandango\Twig\Mandango as MandangoTwig;

/**
 * Archivable.
 *
 * @author Pablo Díez <pablodip@gmail.com>
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
            'step' => 2,
            'collection' => 'counters',
            'counterClass' => 'Core\Models\Counter'
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
        return array(
            'collection' => $this->getOption('collection'),
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
                array('class' => 'Mandango\Behavior\AutoIncrementable'),
            ),
        );
    }


    private function isCounter()
    {
        return !empty($this->configClass['counter']);
    }

    protected function configureTwig(\Twig_Environment $twig)
    {
        $twig->addExtension(new MandangoTwig());
    }
}