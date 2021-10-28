<?php

declare(strict_types=1);

namespace AzPhp\Demo20211026;

class ValidationContext
{
    public function __construct(
        public readonly string $domain,
        public readonly RuleSet $ruleSet,
        private \SplObjectStorage $results = new \SplObjectStorage(),
    ) {}

    public function addResult(Rule $rule, Result $result): void
    {
        $this->results->attach($rule, $result);
    }

    public function getValidCounts(RuleSet $ruleSet): int
    {
        $count = 0;
        foreach ($ruleSet as $rule) {
            $count += $this->getResult($rule)->valid ? 1 : 0;
        }

        return $count;
    }

    public function getValidatedRuleSet(?RuleSet $ruleSet = null): RuleSet
    {
        $ruleSet ??= $this->ruleSet;

        return new RuleSet(
            $ruleSet->operator,
            [...$this->getRules($ruleSet)],
            $this->getResult($ruleSet)->valid,
        );
    }

    /**
     * @return iterable<Rule>
     */
    private function getRules(RuleSet $ruleSet): iterable
    {
        foreach ($ruleSet as $rule) {
            yield ($rule instanceof RuleSet) ? $this->getValidatedRuleSet($rule) : $this->getValidatedRule($rule);
        }
    }

    private function getValidatedRule(RecordRule $rule): RecordRule
    {
        $result = $this->getResult($rule);

        return new RecordRule(
            $rule->prefix,
            $rule->type,
            $rule->expectedValue,
            is_array($result->value) ? implode('; ', $result->value) : $result->value,
            $result->valid,
        );
    }

    private function getResult(Rule $rule): Result
    {
        return $this->results[$rule] ?? throw new \RuntimeException('Result was not available for the rule');
    }
}
