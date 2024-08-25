<?php

declare(strict_types=1);

namespace Sequence\Utils;

use Sequence\TaskInterface;

final class TaskFromCallable
{
    public static function resolve(callable $entry): TaskInterface
    {
        return new TaskClosure($entry);
    }
}
