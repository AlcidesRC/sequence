<?php

declare(strict_types=1);

namespace Fonil\Sequence;

use BadMethodCallException;
use Closure;
use Fonil\Sequence\Exceptions\SequenceException;
use Fonil\Sequence\TaskInterface;
use Fonil\Sequence\Utils\TaskResolver;

/**
 * Class Sequence
 *
 * Accepts:
 *      - Sequence
 *      - TaskInterface
 *      - Closure
 *      - Callable
 *      - Callback
 *      - String
 *
 * Example:
 *      Sequence::run(TaskOne::class)
 *          ->then(TaskTwo::class)
 *          ...
 *          ->then(TaskN::class)
 *          ->startWith($payload)
 *
 * @see https://refactoring.guru/design-patterns/chain-of-responsibility
 */
final class Sequence
{
    private TaskInterface $first;

    private TaskInterface $current;

    /**
     * Set the first link in the chain.
     *
     * @throws SequenceException
     */
    public function __construct(Sequence|TaskInterface|Closure|callable|string $entry)
    {
        $this->first = $this->current = TaskResolver::resolve($entry);
    }

    /**
     * @throws SequenceException
     */
    public static function run(Sequence|TaskInterface|Closure|callable|string $entry): self
    {
        return new self($entry);
    }

    /**
     * Execute all links in the chain.
     */
    public function __invoke(mixed $payload = null): mixed
    {
        return $this->first->startWith($payload);
    }

    /**
     * Set the next link in the chain.
     *
     * @throws SequenceException
     */
    public function then(Sequence|TaskInterface|Closure|callable|string $entry): Sequence
    {
        $this->current = $this->current->then($entry);
        return $this;
    }

    /**
     * Execute all links in the chain.
     */
    public function startWith(mixed $payload = null): mixed
    {
        return $this->first->startWith($payload);
    }
}
