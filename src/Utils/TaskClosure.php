<?php

declare(strict_types=1);

namespace Sequence\Utils;

use Sequence\Task;
use Sequence\TaskInterface;

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
