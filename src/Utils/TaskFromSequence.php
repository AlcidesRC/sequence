<?php

declare(strict_types=1);

namespace Sequence\Utils;

use Sequence\Sequence;
use Sequence\TaskInterface;

final class TaskFromSequence
{
    public static function resolve(Sequence $entry): TaskInterface
    {
        return new TaskClosure($entry);
    }
}
