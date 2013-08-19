Auto-Incrementable
==================

The *AutoIncrementable* behavior implements an auto incrementable field like [AUTO_INCREMENT](http://dev.mysql.com/doc/refman/5.0/en/example-auto-increment.html) at MySQL. This is an atomic operation so no colision is possible. 

> Requires PHP Mongo Driver >1.3.0

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

* ```counterField``` (required): field used to store the last value from the sequence.
* ```counterName``` (required): the name of the sequence to be used. It can be shared between collections.
* ```collection``` (default 'counters'): the collection where the sequences will be stored.
* ```counterClass``` (default 'Model\Counter'): a new model will be created to interact with the sequences. This is its classname.


Usage
-----

The first thing we need to do is initialize the sequence. Elsewise an exception will be rised.


```php
$repository = $mongator->getRepository('Model\Article');
$repository->setSequence();
```

```php
$articleA = $mongator->create('Model\Article')->setTitle('Mongator is fast')->save();

echo $articleA->getIdentifier(); // 1

$articleB = $mongator->create('Model\Article')->setTitle('Mongator is powerful')->save();

echo $articleB->getIdentifier(); // 2
```
