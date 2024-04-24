<?php

declare(strict_types=1);

namespace Fonil\Sequence;

use Closure;
use Fonil\Sequence\Exceptions\SequenceException;

interface TaskInterface
{
    /**
     * Set the next link in the chain.
     *
     * @throws SequenceException
     */
    public function then(Sequence|TaskInterface|Closure|callable|string $entry): TaskInterface;

    /**
     * Execute current link and run the next one if it is defined.
     */
    public function startWith(mixed $payload = null): mixed;

    /**
     * Handle payload.
     */
    public function handle(mixed $payload = null): mixed;
}
