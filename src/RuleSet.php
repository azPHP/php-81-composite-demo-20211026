<?php

declare(strict_types=1);

namespace AzPhp\Demo20211026;

class RuleSet extends Rule implements \IteratorAggregate, \Countable
{
    public static function fromArray(array $data): static
    {
        return new self(
            OperationType::from($data['operator'] ?? throw new \Exception('Requires "operator" field')),
            array_map(Rule::fromArray(...), $data['rules'] ?? throw new \Exception('Requires "rules" field')),
            $data['valid'] ?? null,
        );
    }

    public function __construct(
        public readonly OperationType $operator,
        public readonly array $rules,
        ?bool $valid = null,
    ) {
        parent::__construct($valid);
    }

    /**
     * @return \Traversable<Rule>
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->rules as $rule) {
            yield $rule;
        }
    }

    public function count(): int
    {
        return count($this->rules);
    }

    public function toArray(): array
    {
        return array_filter([
            'operator' => $this->operator->value,
            'rules' => array_map(fn (Rule $rule): array => $rule->toArray(), $this->rules),
            'valid' => $this->valid,
        ], fn ($v) => $v !== null);
    }
}
