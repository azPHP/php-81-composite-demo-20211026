<?php

declare(strict_types=1);

namespace AzPhp\Demo20211026;

class RecordRule extends Rule
{
    public static function fromArray(array $data): static
    {
        return new self(
            $data['prefix'] ?? throw new \Exception('Requires "prefix" field'),
            RecordType::from($data['type'] ?? throw new \Exception('Requires "type" field')),
            $data['expectedValue'] ?? throw new \Exception('Requires "expectedValue" field'),
            $data['currentValue'] ?? null,
            $data['valid'] ?? null,
        );
    }

    public function __construct(
        public readonly string $prefix,
        public readonly RecordType $type,
        public readonly string $expectedValue,
        public readonly ?string $currentValue = null,
        ?bool $valid = null,
    ) {
        parent::__construct($valid);
    }

    public function toArray(): array
    {
        return array_filter([
            'prefix' => $this->prefix,
            'type' => $this->type->value,
            'expectedValue' => $this->expectedValue,
            'currentValue' => $this->currentValue,
            'valid' => $this->valid,
        ], fn ($v) => $v !== null);
    }
}
