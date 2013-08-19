Tokenizable
===========

The *Tokenizable* behavior automatically saves a random token string.

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
                'class' => 'Mongator\Behavior\Tokenizable', 
                'options' => array(
                    'length' => 5
                )
            ),
        ),
    ),
);

```

Options
-------

* ```field``` (default 'token'): field used to store the hash
* ```length``` (default 10): the length of the token

Usage
-----

```php
$article = $mongator->create('Model\Article')->setTitle('Mongator')->save();

echo $article->getToken(); // 9b02d
```
