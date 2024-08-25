<?php

declare(strict_types=1);

namespace Sequence\Utils;

use Sequence\TaskInterface;

final class TaskFromTask
{
    public static function resolve(TaskInterface $entry): TaskInterface
    {
        return $entry;
    }
}
