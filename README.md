# Factrine-Bundle
[![Build Status](https://travis-ci.org/fludio/factrine-bundle.svg?branch=master)](https://travis-ci.org/fludio/factrine-bundle)
[![Coverage Status](https://coveralls.io/repos/fludio/factrine-bundle/badge.svg?branch=master&service=github)](https://coveralls.io/github/fludio/factrine-bundle?branch=master)
[![Quality Score](https://scrutinizer-ci.com/g/fludio/factrine-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fludio/factrine-bundle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/215438b0-217c-46a8-a9d5-5705d2267b1d/mini.png)](https://insight.sensiolabs.com/projects/215438b0-217c-46a8-a9d5-5705d2267b1d)

# Useage

## Create entity files

....

## How to use factrine

### Get a persisted entity

Most of the time you want persist an entity to the database. Call the `create` method and the factory will hand you a new persisted entity

```php
$post = $factory->create(Post::class);

$post->getid();       // 1
$post->getTitle();    // Lorem ipsum dolores
$post->getComments(); // Get a collection of comments
```
Notice that the associated entities will also be generated, if they are specified in the YAML file.

### Multiple entites

Maybe you need a set of entities. You can use the `times` method.

```php
$comments = $factory->times(10)->create(Comment::class);

// You will receive 10 persisted comments with fake values
```

### Override fake data

You might want to override some of the fake data. The create method takes an array as the second argument. Pass in the arguments you want to override.

```php
$user = $factory->create(User::class, ['username' => 'admin']);

$user->getUsername(); // admin
$user->getEmail();    // wizfarrell@downdrum.org (fake data)
```

### Get a new instance

If you don't want to persist the entity, you can call `make` and get a new instance of the entity.

```php
$post = $factory->make(Post::class)

$post->getId()       // null
$post->getTitle()    // Hic clares nombre
```
Of course, you can also use the `times` method or override the default values.

### Get fake values for an entity

Maybe you don't want an instance of the entity, but need some fake data to create you entity object. The `values` method will return an array of fake values for an entity.

```php
$productData = $factory->values(Product::class);

$productData['category'] // Electronics
$productData['price']    // $20.87

$this->productHandler->create($productData);
```

Calling the `times` method, you will receive an array of array with fake data.

