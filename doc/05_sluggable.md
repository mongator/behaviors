Sluggable
=========

The *Sluggable* behavior saves the slug of one field in another.

Configuration
-------------

```php
array(
    'Model\Article' => array(
        'fields' => array(
            'title'   => 'string',
            'content' => 'string',
        ),
        'extensions' => array(
            array(
                'class' => 'Mandango\Behavior\Sluggable', 
                'options' => array(
                    'fromField' => 'title'
                )
            ),
        ),
    ),
);
```

Options
-------

* ```fromField``` (required): field used to generate the slug.
* ```slugField``` (default 'slug'): field used to store the slug.
* ```unique``` (default 'true'): whether the slugs have to be unique. If set to true, a unique index is created.
* ```update``` (default 'false'): whether the slugs can be updated when the field from where the slug is created is modified.
* ```builder``` (default 'Mandango\Behavior\Util\Sluggable::slugify()'): function that converts the base string to slug


Usage
-----

```php
$article = $mongator->create('Model\Article')
    ->setTitle('Mongator is ultrafast!')
    ->save();

echo $article->getSlug(); // mongator-is-ultrafast

$article2 = $mongator->create('Model\Article')
    ->setTitle('Mongator is ultrafast!')
    ->save();

echo $article2->getSlug(); // mongator-is-ultrafast-2
```
