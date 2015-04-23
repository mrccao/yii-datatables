datatables extension
================
datatables
https://datatables.net/

Installation
------------

###1. composer

 * The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

 Either run

 ```
 php composer.phar require --prefer-dist fourteenmeister/yii-datatables "*"
 ```

 or add

 ```
 "fourteenmeister/yii-datatables": "*"
 ```

 to the require section of your `composer.json` file.
 
 * configure the component

 ```php
 'preload' => array(
     ... probably other preloaded components ...
     'datatables'
 ),
 ...
 'components' => array(
     ...
     'datatables' => array(
         'class' => 'webroot.vendor.fourteenmeister.yii-datatables.DataTables', //webroot is it alias => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..\..',
     ),
 )
 ```

###2. github

* download from [github](https://github.com/fourteenmeister/yii-datatables/archive/master.zip)
* extract archive to extension directory.
* configure the component
```php
'preload' => array(
   ... probably other preloaded components ...
   'datatables'
),
...
'components' => array(
   ...
   'datatables' => array(
       'class' => 'ext.yii-datatables.DataTables'
   ),
)
```

Usage
-----

```php
$this->widget('datatables.widgets.GridView',
    array(
        'id' => 'tableID',
        'dataProvider' => $dataProvider,
        'columns' => $columns,
        'theme' => 'base' //theme (bootstrap, jqueryUI, foundation)
    )
);
```