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
                    'fromField' => 'title',
                    'unique' => 'global',
                    'slugClass' => 'Model\Slug',
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
* ```unique``` (default 'local'): whether the slugs have to be unique. If set to local, a unique index is created and checks if exists a collision on the same collection, if you set it to 'global' will try to find a collision in all the collections ussing this behavior with the same config.
* ```update``` (default 'false'): whether the slugs can be updated when the field from where the slug is created is modified.
* ```builder``` (default 'Mandango\Behavior\Util\Sluggable::slugify()'): function that converts the base string to slug

* ```slugClass``` (default 'null'): if unique is set to global, the name of the model name used to store the slugs
* ```collection``` (default 'null'): if unique is set to global, the name of the collection where the slugs are stored
* ```conection``` (default 'null'): if unique is set to global, the name of the conection used to storage the slugs
* ```fromToString``` (default false): if true create the slug from the result of `__toString` method
            



Usage
-----
With ```unique=local```:

```php
$article = $mongator->create('Model\Article')
    ->setTitle('Mongator is ultrafast!')
    ->save();

echo $article->getSlug(); // mongator-is-ultrafast

$article2 = $mongator->create('Model\Article')
    ->setTitle('Mongator is ultrafast!')
    ->save();

echo $article2->getSlug(); // mongator-is-ultrafast-2

$post = $mongator->create('Model\Post')
    ->setTitle('Mongator is ultrafast!')
    ->save();

echo $post->getSlug(); // mongator-is-ultrafast
```

With ```unique=global```:

```php
$article = $mongator->create('Model\Article')
    ->setTitle('Mongator is ultrafast!')
    ->save();

echo $article->getSlug(); // mongator-is-ultrafast

$article2 = $mongator->create('Model\Article')
    ->setTitle('Mongator is ultrafast!')
    ->save();

echo $article2->getSlug(); // mongator-is-ultrafast-2

$post = $mongator->create('Model\Post')
    ->setTitle('Mongator is ultrafast!')
    ->save();

echo $post->getSlug(); // mongator-is-ultrafast-3
```
