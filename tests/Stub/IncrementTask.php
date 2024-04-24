<?php

declare(strict_types=1);

namespace Fonil\Sequence\Tests\Stub;

use Fonil\Sequence\Task;
use Fonil\Sequence\TaskInterface;

class IncrementTask extends Task implements TaskInterface
{
    public function handle(mixed $payload = null): mixed
    {
        // @phpstan-ignore-next-line
        $payload['counter']++;
        return $payload;
    }
}
