datatables extension
================
datatables
https://datatables.net/

Installation
------------

1. composer

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist fourteenmeister/yii-datatables "*"
```

or add

```
"fourteenmeister/yii-datatables": "*"
```

to the require section of your `composer.json` file.

Then there are two ways:
a) insert a line in the file index.php (entry script)

```php
require(__DIR__ . '/protected/vendor/autoload.php')); // path to composer autoload
```

b) append to application configuration

```php

return array(
    ...
    'aliases' => array(
        ...
        'datatables' =>  '/protected/vendor/fourteenmeister/yii-datatables' //Path to source
    )
    ...
)
```

2. github

Usage
-----