<?php

declare(strict_types=1);

namespace Fonil\Sequence\Tests\Unit;

use DateTime;
use Fonil\Sequence\Exceptions\SequenceException;
use Fonil\Sequence\Sequence;
use Fonil\Sequence\TaskInterface;
use Fonil\Sequence\Tests\Stub\Foo;
use Fonil\Sequence\Tests\Stub\IncrementCounter;
use Fonil\Sequence\Tests\Stub\IncrementTask;
use Fonil\Sequence\Tests\Stub\InvokableIncrementCounter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SlopeIt\ClockMock\ClockMock;
use TypeError;
use stdClass;

#[CoversClass(\Fonil\Sequence\Exceptions\SequenceException::class)]
#[CoversClass(\Fonil\Sequence\Sequence::class)]
#[CoversClass(\Fonil\Sequence\Task::class)]
#[CoversClass(\Fonil\Sequence\Utils\TaskClosure::class)]
#[CoversClass(\Fonil\Sequence\Utils\TaskFromCallable::class)]
#[CoversClass(\Fonil\Sequence\Utils\TaskFromSequence::class)]
#[CoversClass(\Fonil\Sequence\Utils\TaskFromString::class)]
#[CoversClass(\Fonil\Sequence\Utils\TaskFromTask::class)]
#[CoversClass(\Fonil\Sequence\Utils\TaskResolver::class)]
final class SequenceTest extends TestCase
{
    protected function setUp(): void
    {
        ClockMock::freeze(new DateTime('2023-01-01 00:00:00'));
    }

    protected function tearDown(): void
    {
        ClockMock::reset();
    }

    // ---------------------------------------------------------------------------------------------------------------

    #[Test]
    #[DataProvider('dataProviderNotSupported')]
    public function checkExceptionIsRaisedWhenTaskIsNotSupported(int $task): void
    {
        $this->expectException(TypeError::class);

        // @phpstan-ignore-next-line
        Sequence::run($task);
    }

    #[Test]
    #[DataProvider('dataProviderNotResolvable')]
    public function checkExceptionIsRaisedWhenTaskIsNotResolvable(string $task): void
    {
        $this->expectException(SequenceException::class);
        $this->expectExceptionCode(SequenceException::getCodeForNotResolvable());
        $this->expectExceptionMessage(SequenceException::getMessageForNotResolvable($task));

        Sequence::run($task);
    }

    #[Test]
    #[DataProvider('dataProviderNotCallable')]
    public function checkExceptionIsRaisedWhenTaskIsNotCallable(string $task): void
    {
        $this->expectException(SequenceException::class);
        $this->expectExceptionCode(SequenceException::getCodeForNotCallable());
        $this->expectExceptionMessage(SequenceException::getMessageForNotCallable($task));

        Sequence::run($task);
    }

    /**
     * @param array<string, int> $payload
     * @param array<string, int> $expectedResult
     */
    #[Test]
    #[DataProvider('dataProviderString')]
    public function checkSequenceCanBeInstantiatedWithString(
        string $task,
        array $payload,
        array $expectedResult
    ): void {
        $result = Sequence::run($task)->startWith($payload);

        self::assertIsArray($result);
        self::assertEquals($expectedResult, $result);
    }

    /**
     * @param array<string, int> $payload
     * @param array<string, int> $expectedResult
     */
    #[Test]
    #[DataProvider('dataProviderCallable')]
    public function checkSequenceCanBeInstantiatedWithCallable(
        callable $task,
        array $payload,
        array $expectedResult
    ): void {
        $result = Sequence::run($task)->startWith($payload);

        self::assertIsArray($result);
        self::assertEquals($expectedResult, $result);
    }

    /**
     * @param array<string, int> $payload
     * @param array<string, int> $expectedResult
     */
    #[Test]
    #[DataProvider('dataProviderTaskInterface')]
    public function checkSequenceCanBeInstantiatedWithTask(
        TaskInterface $task,
        array $payload,
        array $expectedResult
    ): void {
        $result = Sequence::run($task)->startWith($payload);

        self::assertIsArray($result);
        self::assertEquals($expectedResult, $result);
    }

    /**
     * @param array<string, int> $payload
     * @param array<string, int> $expectedResult
     */
    #[Test]
    #[DataProvider('dataProviderSequence')]
    public function checkSequenceCanBeInstantiatedWithSequence(
        Sequence $task,
        array $payload,
        array $expectedResult
    ): void {
        $result = Sequence::run($task)->startWith($payload);

        self::assertIsArray($result);
        self::assertEquals($expectedResult, $result);
    }

    #[Test]
    public function checkSequenceCanBeInstantiatedWithMixedTypes(): void
    {
        $result = Sequence::run([new IncrementCounter(), 'increment'])
            ->then([IncrementCounter::class, 'incrementAsStatic'])
            ->then(InvokableIncrementCounter::class)
            ->then(new IncrementTask())
            ->startWith(['counter' => 0]);

        self::assertIsArray($result);
        self::assertEquals(['counter' => 4], $result);
    }

    // ---------------------------------------------------------------------------------------------------------------

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function dataProviderNotSupported(): array
    {
        return [
            'Number' => [12345],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function dataProviderNotResolvable(): array
    {
        return [
            'String' => ['xxxx'],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function dataProviderNotCallable(): array
    {
        return [
            'Standard class' => [stdClass::class],
            'Empty class'    => [Foo::class],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function dataProviderString(): array
    {
        return [
            'Invokable increment counter' => [
                InvokableIncrementCounter::class,
                ['counter' => 0],
                ['counter' => 1]
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function dataProviderCallable(): array
    {
        $closure = function (array $payload): array {
            $payload['counter']++;
            return $payload;
        };

        return [
            'Closure' => [
                $closure,
                ['counter' => 0],
                ['counter' => 1]
            ],

            'Reference to an instance method' => [
                [new IncrementCounter(), 'increment'],
                ['counter' => 0],
                ['counter' => 1]
            ],

            'Reference to a static class method' => [
                [IncrementCounter::class, 'incrementAsStatic'],
                ['counter' => 0],
                ['counter' => 1]
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function dataProviderTaskInterface(): array
    {
        return [
            'Task instance' => [
                new IncrementTask(),
                ['counter' => 0],
                ['counter' => 1]
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function dataProviderSequence(): array
    {
        return [
            'Sequence instance' => [
                Sequence::run(InvokableIncrementCounter::class),
                ['counter' => 0],
                ['counter' => 1]
            ],
        ];
    }
}
