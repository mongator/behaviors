Ipable
======

The *Ipable* behavior automatically saves the IP from where the documents are created and/or updated.

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
            array('class' => 'Mongator\Behavior\Ipable'),
        ),
    ),
);
```

Options
-------

* ```createdEnabled``` (default true): whether to save the IP from where documents are created 
* ```createdField``` (default 'created_from'): field used to store the IP from where documents are created
* ```updatedEnabled``` (default true): whether to save the IP from where documents are updated
* ```updatedField``` (default 'updated_from'): field used to store the IP from where documents are updated
* ```getIpCallable``` (default 'Mandango\Behavior\Util\IpableUtil::getIp()'): callable that returns the IP to save 

Usage
-----

```php
$article = $mandango->create('Model\Article')->setTitle('Mandango')->save();

echo $article->getCreatedFrom(); // 127.0.0.1
echo $article->getUpdatedFrom(); // null

$article->setContent('Content')->save();

echo $article->getCreatedFrom(); // 127.0.0.1
echo $article->getUpdatedFrom(); // 127.0.0.1
```
