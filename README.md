Flare CQRS
==========

Flare is a small and easy to use CQRS library.  
It drives CQRS by making use of the message bus pattern, separating Queries
(interrogatory messages) from Commands (imperative messages).

This library was greatly inspired by [Messaging Flavours][1] article by Mathias
Verraes.

What is CQRS?
-------------

Originated with Bertrand Meyer's _Command and Query Separation_ principle (CQS):

> It states that every method should either be a command that performs an
  action, or a query that returns data to the caller, but not both. In other
  words, asking a question should not change the answer. More formally, methods
  should return a value only if they are referentially transparent and hence
  possess no side effects.
  
So, _Command and Query Responsibility Segregation_ (CQRS):

> ... applies the CQS principle by using separate Query and Command objects to
  retrieve and modify data, respectively.
  
Source: [Wikipedia][2]

Installation
------------

Through composer:

```shell
$ composer require ignislabs/flare-cqrs
```

Usage
-----

FlareCQRS is framework-agnostic, but it's really easy to bootstrap and use.

### Bootstrapping

```php
<?php
// Instantiate the resolver:
// This will get you a new instance of the hander based on the _handler id_,
// i.e, the fully-qualified class name, or wharever identifier you might use in
// a framework container.
// By default FlareCQRS comes with two resolvers, a CallableResolver and a PSR11Resolver.
// You can always create your own resolver by implementing the `Resolver` contract.

// Generic callable resolver
$resolver = new \IgnisLabs\FlareCQRS\Handler\Resolver\CallableResolver(function($handlerId) {
    // Somehow resolve your handler handler:
    return new performMagicToGetHandlerInstance($handlerId);
});

// PSR11 resolver (assuming Laravel's `$app` container, since it's PSR-11 compliant)
$resolver = new \IgnisLabs\FlareCQRS\Handler\Resolver\PSR11Resolver($app);

// Now instantiate the buses passing them a Locator instance

$queryBus = new \IgnisLabs\FlareCQRS\QueryBus(
    // Tell the Locator which handler corresponds to which query
    // and how to instantiate the handlers (passing in the Resolver)
    new \IgnisLabs\FlareCQRS\Handler\Locator\MapLocator($resolver, [
        GetAllTasksQuery::class => GetAllTasksHandler::class
    ])
);

$commandBus = new \IgnisLabs\FlareCQRS\CommandBus(
    // Tell the Locator which handler corresponds to which command
    // and how to instantiate the handlers (passing in the Resolver)
    new \IgnisLabs\FlareCQRS\Handler\Locator\MapLocator($resolver, [
        AddTaskCommand::class => AddTaskHandler::class
    ])
);
```

### Usage

Now you can use the buses to dispatch any message, like so:

```php
<?php
// Queries can return whatever you need, it will be encapsulated in a Result object
$result = $queryBus->dispatch(new GetAllTasksQuery('some', 'params'));
// You can call `$result->call`:
$result->call(function(TaskCollection $tasks) {
    // Do what you want with your results
    // Using `call` let's you use type-hinting
    // It can be a any `callable`, not just a closure
});
// Or just get the result right away:
$result->getResult();

// Commands do not return anything
$commandBus->dispatch(new AddTaskCommand('Task Title', 'The task description'));

// You can dispatch multiple commands in sequence with a single call
$commandBus->dispatch(
    new AddTaskCommand('Task Title', 'The task description'),
    new UpdateTaskCommand('NEW Task Title', 'The NEW task description')
);
// Or if you like splat!
$commandBus->dispatch(...$commandsArray);
```

### Message (Query & Command) classes

Your Message classes are simple DTO objects, so there are no rules or contracts
to use, they can be whatever you like.

You can, however, take advantage of the `DataAccessorTrait`. With it you can
have automatic accessor properties for your message classes. The trait defines
a `data` private property, a `get` accessor method and the `__get` magic method
so you can access the data as properties:

```php
<?php

class MyMessage {
    use \IgnisLabs\FlareCQRS\Message\DataAccessorTrait;
 
    public function __construct(string $foo, int $bar) {
        $this->setData(compact('foo', 'bar'));
    }
}

$message = new MyMessage('baz', 'qux');
// Using generic `get` accessor:
$message->get('foo'); // returns 'baz'
// Using the magic accessor:
$message->bar; // returns 'qux'
```

### Handlers

A handler can be anything, as long as it is `callable`. Although, you
will probably want to make them classes. In order to make a class be
callable, just implement the `__invoke` method in it.

The `__invoke` method will receive an instance of the corresponding
command.

This way, the classes are 100% yours, no hard dependency on this
library whatsoever and you can typehint freely.

Let's see a quick example:

```php
<?php
class MyMessageHandler {
    public function __invoke(MyMessage $command) {
     // do something here
    }
}
```

### Middlewares

You can create middlewares to interact with the messages before they
reach their respective handlers.

Middlewares, same as with the Handlers, are `callable`s, but you might
prefer them to be classes, by using the same `__invoke` strategy.

You can pass your middlewares globally to your buses on instantiation as
the last parameters like so:

```php
<?php
$callableMiddleware = function() { /* ... */ };
$queryBus = new \IgnisLabs\FlareCQRS\QueryBus(
    $locator, new LoggingMiddleware($logger), new FooMiddleware, $callableMiddleware
);
```

Or you can add/replace the middlewares on a one time basis:

```php
<?php
// Add a middleware to the chain
$commandBus->addMiddleware(new LoggingMiddleware($logger));
// Completely replace the middleware chain
$commandBus->middlewares(new LoggingMiddleware($logger), new FooMiddleware);
```

Buses are **immutable**, so adding or replacing middlewares on a bus will always
return a new bus instance, so any subsequent calls to your buses will not be
affected by these middleware changes.

[1]: http://verraes.net/2015/01/messaging-flavours/
[2]: https://en.wikipedia.org/wiki/Command–query_separation
