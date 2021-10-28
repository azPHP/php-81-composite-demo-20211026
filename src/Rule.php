<?php

declare(strict_types=1);

namespace AzPhp\Demo20211026;

abstract class Rule
{
    public static function fromArray(array $data): static
    {
        return isset($data['operator']) ? RuleSet::fromArray($data) : RecordRule::fromArray($data);
    }

    public function __construct(public readonly ?bool $valid) {}

    abstract public function toArray(): array;
}
