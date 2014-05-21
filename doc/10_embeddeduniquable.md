EmbeddedUniquable
=================

The *EmbeddedUniquable* behavior ensure unique embeddeds document in a embeddedsMany relation, using a given field of this embedded document.


Configuration
-------------

```php
array(
    'Model\Article' => array(
        'embeddedsMany' => array(
            'foo' => array('class' => 'Model\Comment'),
        ),
        'behaviors' => array(
            array(
                'class'   => 'Mongator\Behavior\EmbeddedUniquable',
                'options' => array(
                    'fields' => [[
                        'embeddedsMany' => 'foo', 
                        'field' => 'title'
                    ]],
                ),
            )
        ),
    ),
    'Model\Comment' => array(
        'isEmbedded' => true,
        'fields' => array(
            'title' => 'string',
            'content' => 'string'
        )
    ),
);

```

Options
-------

* ```fields``` (required): array of arrays with a key `embeddedsMany`, with the embeddedsMany name and `field` key with the field name used to match the documents 


Usage
-----

```php
$one = $this->mongator->create('Model\Comment');
$one->setTitle('foo');

$two = $this->mongator->create('Model\Comment');
$two->setTitle('foo');

$document = $this->mongator->create('Model\EmbeddedUniquable');
$document->addFoo($one);
$document->addFoo($two);
$document->save();

echo $document->getFoo(); //Returns 1
```

