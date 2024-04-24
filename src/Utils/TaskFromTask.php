<?php

declare(strict_types=1);

namespace Fonil\Sequence\Utils;

use Fonil\Sequence\TaskInterface;

final class TaskFromTask
{
    public static function resolve(TaskInterface $entry): TaskInterface
    {
        return $entry;
    }
}
