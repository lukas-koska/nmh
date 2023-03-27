<?php

declare(strict_types=1);

namespace App\Rest\ResourceType;

interface RequestInterface
{
    /**
     * Convert object to array
     *
     * @return array<int|string, int|float|string|bool|null>
     */
    public function toArray(bool $snakeCase = false);
}
