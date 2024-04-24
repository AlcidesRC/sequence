<?php

declare(strict_types=1);

namespace Fonil\Sequence\Utils;

use Fonil\Sequence\Task;
use Fonil\Sequence\TaskInterface;

final class TaskClosure extends Task implements TaskInterface
{
    /**
     * @param callable $callable
     */
    public function __construct(
        private $callable
    ) {
    }

    /**
     * Handle payload.
     */
    public function handle(mixed $payload = null): mixed
    {
        return call_user_func_array($this->callable, [$payload]);
    }
}
