<?php

declare(strict_types=1);

namespace Fonil\Sequence\Utils;

use Fonil\Sequence\TaskInterface;

final class TaskFromCallable
{
    public static function resolve(callable $entry): TaskInterface
    {
        return new TaskClosure($entry);
    }
}
