Identifiable
===========

The *Identifiable* behavior automatically saves an identifier in the document. 

NOTE: This behavior only can be used with EmbeddedDocuments

Configuration
-------------

```php
array(
    'Model\Article' => array(
        'fields' => array(
            'field' => 'string',
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Identifiable', 
                'options' => array(
                )
            ),
        ),
    ),
);

```

Options
-------

* ```idField``` (default 'token'): field used to store the id
* ```idGeneratorMethod``` (default null): callable that returns the id 
