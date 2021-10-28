<?php

declare(strict_types=1);

namespace AzPhp\Demo20211026;

class Result
{
    public function __construct(
        public readonly bool $valid,
        public readonly string|array|null $value = null,
    ) {}
}
