<?php

declare(strict_types=1);

namespace Sequence\Utils;

use Closure;
use Sequence\Sequence;
use Sequence\Exceptions\SequenceException;
use Sequence\Task;
use Sequence\TaskInterface;

final class TaskResolver
{
    /**
     * @throws SequenceException
     */
    public static function resolve(Sequence|TaskInterface|Closure|callable|string $entry): TaskInterface
    {
        if ($entry instanceof TaskInterface) {
            return TaskFromTask::resolve($entry);
        }

        if ($entry instanceof Sequence) {
            return TaskFromSequence::resolve($entry);
        }

        if (is_string($entry)) {
            return TaskFromString::resolve($entry);
        }

        if (is_callable($entry)) {
            return TaskFromCallable::resolve($entry);
        }
    }
}
