Stringifiable
=============

The *Stringifiable* behavior add to the document the magic method __toString, so any document can be converted to string. 


Configuration
-------------

```php
array(
    'Model\Article' => array(
        'fields' => array(
            'field' => 'string',
        ),
        'behaviors' => array(
            'class' => 'Mongator\Behavior\Stringifiable',
            'options' => array(
                'fromFields' => array(
                    'title',
                    'date' => 'Y-m-d\TH:i:sP'
                )
            ),
        ),
    ),
);

```

Options
-------

* ```fromFields``` (required): array of fields to be used as output, you can use any field and any referenceField or embededDocument using this behavior too.
* ```format``` (default '%s' n times, many as fields): the format of the output in printf style.


Usage
-----

```php
$article = $mongator->create('Model\Article');
$article->setTitle(' Testing Sluggable Extensión ');
$article->setDate(new \DateTime());

echo $article; // Testing Sluggable Extensión 2013-10-10T00:00:00+00:00
```

