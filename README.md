[![Build Status](https://travis-ci.org/Padam87/SearchBundle.png)](https://travis-ci.org/Padam87/SearchBundle)
[![Coverage Status](https://coveralls.io/repos/Padam87/SearchBundle/badge.png)](https://coveralls.io/r/Padam87/SearchBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/Padam87/SearchBundle/badges/quality-score.png?s=9b1c88ceb9bd4fe2d50d2a283f21b7a2f33b6299)](https://scrutinizer-ci.com/g/Padam87/SearchBundle/)

# Search Bundle #

Search bundle for Symfony2. Use entities, collections for search directly.

## 1. Examples ##

### 1.1 Simple ###

```php
$qb = $this->get('search')->createFilter($filter, 'alias')->createQueryBuilder('YourBundle:Entity');
```

### 1.2 Joins ###

```php
$qb =
$this->get('search')->createFilter($joinedfilter, 'joinalias')->applyToQueryBuilder(
    $this->get('search')->createFilter($filter, 'alias')->createQueryBuilder('YourBundle:Entity'),
    'relationName' // this is the name of the relation in your entity, eg 'users'
);
```

### 1.3 Collections ###

You can also create a collection filter, which will use all the entities in the collection, to search.

#### 1.3.1 OR ####

Just like the simple example, but in this case, the filter is a doctrine collection.

```php
$qb = $this->get('search')->createFilter($filter, 'alias')->createQueryBuilder('YourBundle:Entity');
```

#### 1.3.2 AND ####

```php
$qb = $this->get('search')->createFilter($filter, 'alias')->createQueryBuilder('YourBundle:Entity', array(
    'relationName' => 'AND'
));
```

## 2. Installation ##

### 2.1. Composer ###

    "padam87/search-bundle": "1.0.*",

### 2.2. AppKernel ###

    $bundles = array(
		...
        new Padam87\SearchBundle\Padam87SearchBundle(),
    );


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/Padam87/searchbundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

