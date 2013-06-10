Archivable
==========

The *Archivable* behavior makes a copy of document in other collection, on-demand or when the document is created/updated/deleted


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
                'class' => 'Mongator\Behavior\Archivable',
            ),
        ),
    ),
);

```

Options
-------

* ```archive_class``` (default '%class%\\Archive'): the new model used to make the copies
* ```collection_class``` (default '%collection%Archive'): the collection where the copies will be stored
* ```id_field``` (default 'document'): the field in the copy where the original id will be saved
* ```archived_at_field``` (default 'archived_at_field'): the field in the origina where the archived id will be saved
* ```archive_on_insert``` (default false): create a copy of the document when is created
* ```archive_on_update``` (default false): create a copy of the document when is updated
* ```archive_on_delete``` (default true): create a copy of the document when is deleted
* ```fields``` (default array()): the list of fields used to create the copy, if empty all the fields will be used


Usage
-----

```php
$article = $this->mongator->create('Model\Article')
    ->setTitle('foo')
    ->save();

$id = $article->getId();
$article->delete();

$copy = $this->mongator
    ->getRepository('Model\Article\Archive')
    ->createQuery(array('document' => $id))
    ->one();

echo $copy->getTitle(); // foo
```