<?php

declare(strict_types=1);

namespace Sequence\Tests\Stub;

use Sequence\Task;
use Sequence\TaskInterface;

class IncrementTask extends Task implements TaskInterface
{
    public function handle(mixed $payload = null): mixed
    {
        // @phpstan-ignore-next-line
        $payload['counter']++;
        return $payload;
    }
}
