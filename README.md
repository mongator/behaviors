Mongator Behaviors [![Build Status](https://travis-ci.org/mongator/behaviors.png?branch=master)](https://travis-ci.org/mongator/behaviors)
==============================

These are the official behaviors of Mongator.

- Timestampable: saves the creation and/or update date in the documents
- Ipable: saves the IP from where documents are created and/or saved
- Sluggable: saves the slug of a field in the documents
- Archivable: Save a document copy from one collection to other
- Tokenizable: Generate a token on creation
- Hasable: Generate a hash for a given fields/rels/embs from the given document, on update and or creation

Requirements
------------

* PHP 5.3.x;
* mongator/mongator


Installation
------------

The recommended way to install Mongator Behaviors is [through composer](http://getcomposer.org).
You can see [package information on Packagist.](https://packagist.org/packages/mongator/behaviors)

```JSON
{
    "require": {
        "mongator/behaviors": "dev"
    }
}
```


Examples
--------
On your document definition just add a new array named behaviors, just like this:

```php
'Model\MyCollecion' => array(
    'fields' => array(
        'title' => 'string',
    ),
    'behaviors' => array(
        array('class' => 'Mongator\Behavior\Tokenizable'),
        array('class' => 'Mongator\Behavior\Archivable'),
    ),
),
```

Tests
-----

Tests are in the `tests` folder.
To run them, you need PHPUnit.
Example:

    $ phpunit --configuration phpunit.xml.dist


License
-------

MIT, see [LICENSE](LICENSE)