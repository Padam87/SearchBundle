[![Build Status](https://travis-ci.org/Padam87/SearchBundle.png)](https://travis-ci.org/Padam87/SearchBundle)
[![Coverage Status](https://coveralls.io/repos/Padam87/SearchBundle/badge.png)](https://coveralls.io/r/Padam87/SearchBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/Padam87/SearchBundle/badges/quality-score.png?s=9b1c88ceb9bd4fe2d50d2a283f21b7a2f33b6299)](https://scrutinizer-ci.com/g/Padam87/SearchBundle/)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/cd38769c-b30b-4d6a-88ce-e1906b35eee2/mini.png)](https://insight.sensiolabs.com/projects/cd38769c-b30b-4d6a-88ce-e1906b35eee2)
[![Latest Stable Version](https://poser.pugx.org/padam87/search-bundle/v/stable.png)](https://packagist.org/packages/padam87/search-bundle)
[![Total Downloads](https://poser.pugx.org/padam87/search-bundle/downloads.png)](https://packagist.org/packages/padam87/search-bundle)
[![Latest Unstable Version](https://poser.pugx.org/padam87/search-bundle/v/unstable.png)](https://packagist.org/packages/padam87/search-bundle)
[![License](https://poser.pugx.org/padam87/search-bundle/license.png)](https://packagist.org/packages/padam87/search-bundle)

# Search Bundle #

Search bundle for Symfony2. Use entities, collections for search directly. Great for handling complex search forms.

## 1. Examples ##

### 1.1 Simple ###

```php
$fm = $this->get('padam87_search.filter.manager');
$filter = new Filter($data, 'YourBundle:Entity', 'alias');
$qb = $fm->createQueryBuilder($filter);
```
```$data``` can be an ***array***, an ***entity***, or even a doctrine ***collection***.

You can add your own converter to handle any type of data.

### 1.2 Joins ###

```php
$fm = $this->get('padam87_search.filter.manager');
$filter1 = new Filter($data1, 'YourBundle:Entity1', 'alias1');
$filter2 = new Filter($data2, 'YourBundle:Entity2', 'alias2');
$qb = $fm->joinToQueryBuilder($filter2, $fm->createQueryBuilder($filter1), 'associationName');
```

```'associationName'``` is the name of the relation in your entity, eg 'users'

### 1.3 Collection valued associations ###

```php
$fm = $this->get('padam87_search.filter.manager');
$filter = new Filter($data, 'YourBundle:Entity', 'alias');
$qb = $fm->createQueryBuilder($filter);
```

When ```$data``` is an entity, it can have *ToMany associations. By default, the bundle assumes OR relationship between the elements of the collection. To change that, you can use the 2nd parameter of ```$fm->createQueryBuilder```:

```php
$fm = $this->get('padam87_search.filter.manager');
$filter = new Filter($data, 'YourBundle:Entity', 'alias');
$qb = $fm->createQueryBuilder($filter, array(
    'relationName' => 'AND'
));
```

### 1.4 Operators ###

```php
$data = array(
    'integerField>=' => 10
    'stringFiled' => 'A*'
);
$filter = new Filter($data, 'YourBundle:Entity', 'alias');
```

The bundle will search for operators in the field names and values, and use the appropriate Expr.


For a nicer, and entity-compatible solution you can use the 4th parameter of the Filter to set default operators:


```php
$filter = new Filter($data, 'YourBundle:Entity', 'alias', array(
    'integerField' => '>='
));
```


## 2. Installation ##

### 2.1. Composer ###

    "padam87/search-bundle": "2.0.*",

### 2.2. AppKernel ###

    $bundles = array(
		...
        new Padam87\SearchBundle\Padam87SearchBundle(),
    );
