Flare CQRS
==========

Flare is a small and easy to use CQRS library.  
It drives CQRS by making use of the message bus pattern, separating Queries
(interrogatory messages), Commands (imperative messages), and Events
(informational messages).

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

// # QueryBus

// Tell the Router which handler corresponds to which query
// and how to instantiate the handlers (passing in the Resolver)
$queryRouter = new \IgnisLabs\FlareCQRS\Handler\Router\SingleHandlerRouter($resolver);
$queryRouter->add(GetAllTasksQuery::class, GetAllTasksHandler::class);
$queryRouter->add(GetTaskQuery::class, GetTaskHandler::class);
// Now instantiate the buses passing them a Router instance
$queryBus = new \IgnisLabs\FlareCQRS\QueryBus($queryRouter);

// # CommandBus

// Tell the Router which handler corresponds to which command
// and how to instantiate the handlers (passing in the Resolver)
$commandRouter = new \IgnisLabs\FlareCQRS\Handler\Router\SingleHandlerRouter($resolver);
$commandRouter->add(AddTaskCommand::class, AddTaskHandler::class);
// Now instantiate the buses passing them a Router instance
$commandBus = new \IgnisLabs\FlareCQRS\CommandBus($commandRouter);

// # EventBus

// In this case, we use the `MultiHandlerRouter`
// Tell the Router which handlers corresponds to which event
// and how to instantiate the handlers (passing in the Resolver)
$eventRouter = new \IgnisLabs\FlareCQRS\Handler\Router\MultiHandlerRouter($resolver);
$eventRouter->add(TaskWasAdded::class, DoSomethingWhenTaskIsAdded::class);
$eventRouter->add(TaskWasAdded::class, DoSomethingElseWhenTaskIsAdded::class);
// Now instantiate the buses passing them a Router instance
$eventBus = new \IgnisLabs\FlareCQRS\CommandBus($eventRouter);
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

// Commands usually do not return anything, but they can return events from your handlers
$events = $commandBus->dispatch(new AddTaskCommand('Task Title', 'The task description'));

// Events do not return anything
$eventBus=>dispatch(new TaskWasAdded($task->toArray()));
```

### Message (Query, Command, & Event) classes

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
library whatsoever and you can type-hint freely.

Let's see a quick example:

```php
<?php
class MyMessageHandler {
    public function __invoke(MyMessage $command) {
     // do something here
    }
}
```

### Generating Events

You can generate events however you want, but we included a convenient
trait for you to use in your entities/models, handlers, or wherever you
may need to.

Let's see a usage example:

```php
<?php
class User {
    use \IgnisLabs\FlareCQRS\Traits\GeneratesEvents;
    // ...
    public static function signUp($id, $email, $password) {
        $user = new static($id, $email);
        $user->setPassword($password);
        $user->recordThat(new UserHasSignedUp($user->toArray()));
        return $user;
    }
    // ...
}

class SignUpUserHandler {
    public function __invoke(SignUpUser $command) {
        $user = User::register($command->id, $command->email, $command->password);
        return $user->getRecordedEvents();
    }
}
```

### Automatic event dispatching from command handlers

Your command handlers may return and/or yield events, but in order for
the `CommandBus` to automatically dispatch the events, you need to
enable this by passing to it the `EventBus`:

```php
<?php
/* @var \IgnisLabs\FlareCQRS\CommandBus $commandBus */
$commandBus->dispatchesEvents($eventBus);
$commandBus->dispatch(new SomeCommand('foo'));
```

Let's see a quick example of what the command handler may look like
based on the previous **Generating Events** example:

```php
<?php
class SignUpUserHandler {
    public function __invoke(SignUpUser $command) {
        // You can yield events
        yield new SomeEvent;
        
        $user = User::register($command->id, $command->email, $command->password);
        // And/Or return an array of events
        return $user->getRecordedEvents();
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
