<?php

declare(strict_types=1);

namespace Sequence\Utils;

use Sequence\Exceptions\SequenceException;
use Sequence\TaskInterface;
use Throwable;

final class TaskFromString
{
    /**
     * @throws SequenceException
     */
    public static function resolve(string $entry): TaskInterface
    {
        $entry = self::make($entry);

        self::check($entry);

        // @phpstan-ignore-next-line
        return new TaskClosure($entry);
    }

    /**
     * @throws SequenceException (Not Resolvable)
     */
    private static function make(string $entry): mixed
    {
        try {
            return new $entry();
        } catch (Throwable $throwable) {
            throw SequenceException::notResolvable($entry, $throwable);
        }
    }

    /**
     * @throws SequenceException (Not Callable)
     */
    private static function check(mixed $entry): void
    {
        if (!is_callable($entry)) {
            // @phpstan-ignore-next-line
            throw SequenceException::notCallable(get_class($entry) ?: serialize($entry));
        }
    }
}
