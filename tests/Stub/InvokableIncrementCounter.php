<?php

declare(strict_types=1);

namespace Fonil\Sequence\Tests\Stub;

class InvokableIncrementCounter
{
    /**
     * @param array<string, int> $payload
     * @return array<string, int>
     */
    public function __invoke(array $payload): array
    {
        $payload['counter']++;
        return $payload;
    }
}
