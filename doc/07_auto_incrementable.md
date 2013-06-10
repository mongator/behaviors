Auto-Incrementable
==================

The *AutoIncrementable* behavior implements an auto incrementable field like [AUTO_INCREMENT][http://dev.mysql.com/doc/refman/5.0/en/example-auto-increment.html] at MySQL. This is an atomic operation so no colision is possible. 

> Just works with PHP Mongo Driver >1.3.0

Configuration
-------------

```php
array(
    'Model\Article' => array(
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\AutoIncrementable',
                'options' => array(
                    'counterName' => 'sequence',
                    'counterField' => 'identifier',
                )
            ),
        ),
    ),
);

```

Options
-------

* ```counterField``` (required): field used to store the last value from the sequence
* ```counterName``` (required): the name of the sequence to be used, you can share it between collections
* ```collection``` (default 'counters'): the collection where the sequences will be stored
* ```counterClass``` (default 'Model\Counter'): a new model will be create to interact with the sequences, this is the classname of it.


Usage
-----

The first will need to do is initializete the sequence, if not a exception will be rised.


```php
$repository = $this->mongator->getRepository('Model\Article');
$repository->setSequence();
```

```php
$articleA = $mongator->create('Model\Article')->setTitle('Mongator is fast')->save();

echo $articleA->getIdentifier(); // 1

$articleB = $mongator->create('Model\Article')->setTitle('Mongator is powerfull')->save();

echo $articleB->getIdentifier(); // 2
```