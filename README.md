# Repositories for Laravel 4

*Repository* is a powerful implementation of the repository pattern for Laravel 4 with a simple API.

To install *Repository* in your Laravel project simple execute `composer require lukaskorl/repository`.

**Word of caution**: *Repository* is currently under heavy development. I will release a stable version 1.0.0 soon. Until then the interface may change. I do not recommend to use *Repository* as a `dev-*` dependency in production code.

## Introduction

A repository acts as a mediator between the domain logic and the data mapping layer. This means the repository sits between your code handling your business cases and the code accessing databases, APIs, filesystem, ...

You may benefit from using a repository in many ways:

 - Complicated queries on your data may be encapsulated in the repository and accessed by your business logic anywhere with a simple method call.
 - The complete data representation may be switched from a database to an API without touching a single line in your business logic.
 - A repository may decouple your database columns from the rest of your code. By transforming attribute names and applying the correct data type to an attribute.
 
There are other benefits which I won't discuss here. If you want to read more about the repository pattern continue reading on [Martin Fowler's website](http://martinfowler.com/eaaCatalog/repository.html).

## Basic Usage

*Repository* provides you with a set of tools to conviniently implement the repository pattern. By default *Repository* only implements CRUD-style querying. But feel free to enhance your repositores with other methods and complex querying.

A simple use case scenario include three tools provided by *Repository*:


 - **(optinally)** provide an implementation of the abstract class `Lukaskorl\Repository\Transformable\Transformer`. This class will handle renaming and typing of attributes.
 - Implementation of the `Lukaskorl\Repository\AbstractRepository` (i.e. `Lukaskorl\Repository\EloquentRepository` if you want to use Eloquent as an ORM). Feel free to implement your own repository strategy i.e. to access an API or other databases. I will add more implementations in the future.
 - `Lukaskorl\Repository\RepositoryController`
 
### Transformers

The usage of a transformer is optionally. If you do not want to rename attributes or to ensure typing you can use *Repository* without transformers. Typically your transformer will look like this:

	<?php namespace Acme\Transformers;
 
	use Lukaskorl\Repository\Transformable\Transformer;

	class PostsTransformer extends Transformer {

    	protected $definitions = [
    	    'id' => 'int',
    	    'active' => 'boolean'
    	];

    	protected $aliases = [
    	    'id' => 'identifier'
    	];

	} 

`$definitions` is an associative array acting as a mapping between fields and datatypes. This datatypes will be applied to the attribute when the entity is returned.

`$aliases` are used to hide the internal database column names and only expose a concise API.

For more information about how transformers work visit the GitHub page of [ConnorVG/laravel-transform](https://github.com/ConnorVG/laravel-transform).

### Repository

A typical implementation of a repository in Laravel 4 will look like:

	<?php namespace Acme\Repository;

	use Acme\Transformers\PostsTransformer;
	use Lukaskorl\Repository\EloquentRepository;

	class EloquentPostsRepository extends EloquentRepository {
	
	    protected $model = "Post";

	    public function __construct(PostsTransformer $transformer)
	    {
        	$this->transformer = $transformer;
   		}

	}
	
By typehinting the `PostsTransformer` the Laravel IoC will automatically inject your transformer on instantiation of your repository. The `$model` member contains the classname of the Eloquent model that will be used to query the database.

### Controller

A controller which automatically makes use of a repository on all CRUD operations is provided in the package. You can simply extend from `Lukaskorl\Repository\RepositoryController`. This controller contains a default implementation but feel free to overwrite any method to change it's behavior.

	<?php

	use Acme\Repository\EloquentPostsRepository;
	use Lukaskorl\Repository\RepositoryController;

	class PostsController extends RepositoryController {

    	public function __construct(EloquentPostsRepository $repository)
    	{
        	$this->setRepository($repository);
	    }

	}
	
If you typhint the constructor with a repository implementation the Laravel IoC will automatically inject the repository in your controller. Just set the repository on your controller and you are done.

By default the controller will respond in a *RESTful* way. For more information about the restful behavior checkout [RESTful on GitHub](https://github.com/lukaskorl/restful).

## Hooks

Repositories will fire various events on various actions. The name pattern for repository events is `repository.<DOMAIN>.<ACTION>` where `<DOMAIN>` is the name of the model used in the `EloquentRepository`. Currently there are these `<ACTION>`s triggered:

 * `repository.<DOMAIN>.creating` is triggered **before** the model is persisted. If you want to register a validation service hook onto this event and throw an exception if validation fails. If you extend the `Lukaskorl\Repository\RepositoryController` **create** will automatically intercept `Lukaskorl\Repository\Validation\ValidationException`s and return a restful *422* response.
 * `repository.<DOMAIN>.created` is triggered **after** the model is persisted.
 
**Beware**: If your model is under a namespace (i.e. `Acme\Stuff\Something`) the `<DOMAIN>` of the event will be in object dot notation like: `repository.acme.stuff.something.<ACTION>`!

## License

*Repository* is open-source software licensed under the [MIT license](http://opensource.org/licenses/MIT)
