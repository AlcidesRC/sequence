[![Continuous Integration](https://github.com/fonil/sequence/actions/workflows/ci.yml/badge.svg)](https://github.com/fonil/sequence/actions/workflows/ci.yml)


# Sequence


> Sequence your tasks and make complex workflows more readable


[TOC]


## Summary

This repository contains a [Chain of Responsability](https://refactoring.guru/design-patterns/chain-of-responsibility) design pattern implementation built with PHP.

## Requirements

This library requires PHP^8.3

## Installation

Install `Sequence` using Composer:

```bash
composer require fonil/sequence
```

## Usage

Create a `Sequence` instance and attach any type of payload through a simple interface:

```php
$result = Sequence::run(FirstTask::class)
	->then(SecondTask::class)
	...
	->then(LastTask::class)
	->startWith('payload');
```

### Tasks

`Sequence` requires at least a task to be run. You can attach any of the following entities as a task:

- [Invokable Class](#invokable-class)
- [Explicit Task](#explicit-task)
- [Custom Class](#custom-class)
- [Static Method](#static-method)
- [Closure / Callback / Callable](#closure--callback--callable)

#### Invokable Class

```php
class InvokableIncrementCounter
{
    public function __invoke(array $payload): array
    {
        $payload['counter']++;
        return $payload;
    }
}

$result = Sequence::run(InvokableIncrementCounter::class)
    ->startWith(['counter' => 0]);

echo json_encode($result);
// {"counter":1}
```

#### Explicit Task

```php
class IncrementTask implements TaskInterface
{
    //...
    
    public function handle(mixed $payload = null): mixed
    {
        $payload['counter']++;
        return $payload;
    }
}

$result = Sequence::run(IncrementTask::class)
    ->startWith(['counter' => 0]);

echo json_encode($result);
// {"counter":1}
```

#### Custom Class

```php
class IncrementCounter
{
    public function add(array $payload): array
    {
        $payload['counter']++;
        return $payload;
    }
}

$result = Sequence::run([IncrementCounter::class, 'add'])
    ->startWith(['counter' => 0]);

// OR

$result = Sequence::run([new IncrementCounter(), 'add'])
    ->startWith(['counter' => 0]);

echo json_encode($result);
// {"counter":1}
```

#### Static Method

```php
class IncrementCounter
{
    public static function add(array $payload): array
    {
        $payload['counter']++;
        return $payload;
    }
}

$result = Sequence::run([IncrementCounter::class, 'add'])
    ->startWith(['counter' => 0]);

echo json_encode($result);
// {"counter":1}
```

#### Closure / Callback / Callable

```php
$closure = function (array $payload): array {
    $payload['counter']++;
    return $payload;
};

$result = Sequence::run($closure)
    ->startWith(['counter' => 0]);

echo json_encode($result);
// {"counter":1}
```

#### Sequence Instance

```php
$closure = function (array $payload): array {
    $payload['counter']++;
    return $payload;
};

$sequence = Sequence::run($closure)->then($closure);

$result = Sequence::run($sequence)
    ->startWith(['counter' => 0]);

echo json_encode($result);
// {"counter":2}
```

### Examples

```php
$result = Sequence::run($closure)			// 1st execution => $counter is 1
	->then(InvokableIncrementCounter::class)	// 2nd execution => $counter is 2
	->then([IncrementCounter::class, 'increment'])	// 3rd execution => $counter is 3
	->then(IncrementTask::class)			// 4th execution => $counter is 4
	->startWith(['counter' => 0]);

echo json_encode($result);
// {"counter":4}
```


## Security Vulnerabilities

Please review our security policy on how to report security vulnerabilities:

**PLEASE DON'T DISCLOSE SECURITY-RELATED ISSUES PUBLICLY**

### Supported Versions

Only the latest major version receives security fixes.

### Reporting a Vulnerability

If you discover a security vulnerability within this project, please [open an issue here](https://github.com/fonil/sequence/issues). All security vulnerabilities will be promptly addressed.

## License

The MIT License (MIT). Please see [LICENSE](./LICENSE) file for more information.
