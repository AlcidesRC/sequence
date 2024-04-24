<?php

declare(strict_types=1);

namespace Fonil\Sequence\Utils;

use Fonil\Sequence\Sequence;
use Fonil\Sequence\TaskInterface;

final class TaskFromSequence
{
    public static function resolve(Sequence $entry): TaskInterface
    {
        return new TaskClosure($entry);
    }
}
