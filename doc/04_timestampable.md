Timestampable
=============

The *Timestampable* behavior saves automatically the creation and/or update date in the documents.

Configuration
-------------

```php
array(
    'Model\Article' => array(
        'fields' => array(
            'title'   => 'string',
            'content' => 'string',
        ),
        'behaviors' => array(
            array('class' => 'Mandango\Behavior\Timestampable'),
        ),
    ),
);

```

Options
-------

* ```createdEnabled``` (default true): if saving or not the creation date (enabled by default)
* ```createdField``` (default 'created_at'): field used to store the creation date (*created_at* by default)
* ```updatedEnabled``` (default true): if saving or not the update date (enabled by default)
* ```updatedField``` (default 'updated_at'): field used to store the update date (*updated_at* by default)


Usage
-----

```php
$article = $mongator->create('Model\Article')->setTitle('Mongator')->save();

echo $article->getCreatedAt(); // new \DateTime('now')
echo $article->getUpdatedAt(); // null

$article->setContent('Content')->save();

echo $article->getCreatedAt(); // the same \DateTime()
echo $article->getUpdatedAt(); // new \DateTime('now')
```