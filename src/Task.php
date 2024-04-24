<?php

declare(strict_types=1);

namespace Fonil\Sequence;

use Closure;
use Fonil\Sequence\Exceptions\SequenceException;
use Fonil\Sequence\Utils\TaskResolver;

abstract class Task
{
    private TaskInterface $next;

    /**
     * Set the next link in the chain.
     *
     * @throws SequenceException
     */
    public function then(Sequence|TaskInterface|Closure|callable|string $entry): TaskInterface
    {
        return $this->next = TaskResolver::resolve($entry);
    }

    /**
     * Execute current link and run the next one if it is defined.
     */
    public function startWith(mixed $payload = null): mixed
    {
        $result = $this->handle($payload) ?: $payload;

        return isset($this->next)
            ? $this->next->startWith($result)
            : $result;
    }

    /**
     * Handle payload.
     */
    abstract public function handle(mixed $payload = null): mixed;
}
