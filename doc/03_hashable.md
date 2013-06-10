Hashable
========

The *Hashable* behavior saves automatically a hash string based on all or some fields from the document. 

> Works with all type of fields, relations or embedded documents.

Configuration
-------------

```php
array(
    'Model\Article' => array(
        'fields' => array(
            'title' => 'string',
            'content' => 'string'
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Hashable'
                'fromFields' => array(
                    'title'
                )
            )
        ),
    ),
);

```

Options
-------

* ```createdEnabled``` (default true): if the hash will be saved when the documents are created
* ```updatedEnabled``` (default true): if the hash will be saved when the documents are updated
* ```field``` (default 'hash'): field used to store the hash
* ```fromFields``` (default array()): the list of fields used to create the hash, if empty all the fields will be used

Usage
-----

```php
$article = $mongator->create('Model\Article')->setTitle('Mongator')->save();

echo $article->getHash(); // 4b8c729a6052a92c6e0bce7b6f119e63
```