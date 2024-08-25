<?php

declare(strict_types=1);

namespace Sequence\Exceptions;

use Exception;
use Throwable;

/**
 * Example:
 *      - throw SequenceException::notCallable(Foo::class)
 *      - throw SequenceException::notResolvable(Foo::class)
 */
final class SequenceException extends Exception
{
    private const NOT_CALLABLE = 'NOT_CALLABLE';
    private const NOT_RESOLVABLE = 'NOT_RESOLVABLE';

    private const EXCEPTION_PATTERN = [
        self::NOT_CALLABLE   => 'Class [ {CLASSNAME} ] is not callable',
        self::NOT_RESOLVABLE => 'Class [ {CLASSNAME} ] is not resolvable',
    ];

    private const EXCEPTION_CODE = [
        self::NOT_CALLABLE   => 100,
        self::NOT_RESOLVABLE => 200,
    ];

    public static function notCallable(string $className, ?Throwable $previous = null): self
    {
        return new self(
            self::getMessageForNotCallable($className),
            self::getCodeForNotCallable(),
            $previous
        );
    }

    public static function notResolvable(string $className, ?Throwable $previous = null): self
    {
        return new self(
            self::getMessageForNotResolvable($className),
            self::getCodeForNotResolvable(),
            $previous
        );
    }

    public static function getMessageForNotCallable(string $className): string
    {
        return strtr(self::EXCEPTION_PATTERN[self::NOT_CALLABLE], [
            '{CLASSNAME}' => $className,
        ]);
    }

    public static function getMessageForNotResolvable(string $className): string
    {
        return strtr(self::EXCEPTION_PATTERN[self::NOT_RESOLVABLE], [
            '{CLASSNAME}' => $className,
        ]);
    }

    public static function getCodeForNotCallable(): int
    {
        return self::EXCEPTION_CODE[self::NOT_CALLABLE];
    }

    public static function getCodeForNotResolvable(): int
    {
        return self::EXCEPTION_CODE[self::NOT_RESOLVABLE];
    }
}
