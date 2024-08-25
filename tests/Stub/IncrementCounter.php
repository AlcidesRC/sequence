<?php

declare(strict_types=1);

namespace Sequence\Tests\Stub;

class IncrementCounter
{
    /**
     * @param array<string, int> $payload
     * @return array<string, int>
     */
    public function increment(array $payload): array
    {
        $payload['counter']++;
        return $payload;
    }

    /**
     * @param array<string, int> $payload
     * @return array<string, int>
     */
    public static function incrementAsStatic(array $payload): array
    {
        $payload['counter']++;
        return $payload;
    }
}
