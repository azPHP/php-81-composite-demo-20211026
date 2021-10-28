<?php

declare(strict_types=1);

namespace AzPhp\Demo20211026;

use Psr\Log\LoggerInterface;

class Validator
{
    public function __construct(
        private DnsResolver $resolver,
        private LoggerInterface $logger,
    ) {}

    public function validate(string $domain, RuleSet $ruleSet): RuleSet
    {
        $context = new ValidationContext($domain, $ruleSet);
        $this->validateRuleSet($context, $ruleSet);

        return $context->getValidatedRuleSet();
    }

    private function validateRuleSet(ValidationContext $context, RuleSet $ruleSet): void
    {
        foreach ($ruleSet as $rule) {
            if ($rule instanceof RuleSet) {
                $this->validateRuleSet($context, $rule);
            } else {
                $this->validateRule($context, $rule);
            }
        }

        $this->aggregateResults($context, $ruleSet);
    }

    private function validateRule(ValidationContext $context, RecordRule $rule): void
    {
        try {
            $domain = $rule->prefix === '.' ? $context->domain : "{$rule->prefix}.{$context->domain}";
            $record = $this->resolver->resolve($domain, $rule->type);
            $result = $this->determineResult($rule, $record);
        } catch (\Throwable) {
            $result = new Result(false);
            $this->logger->warning('Could not validate rule', compact('domain', 'rule', 'record', 'result'));
        }

        $context->addResult($rule, $result);
    }

    private function determineResult(RecordRule $rule, Record $record): Result
    {
        // LOGIC NOT IMPLEMENTED. Using random for simulation.
        $valid = (bool) random_int(0, 1);
        $value = random_int(0, 1) ? 'foo' : ['fizz', 'buzz'];

        return new Result($valid, $valid ? $rule->expectedValue : $value);
    }

    private function aggregateResults(ValidationContext $context, RuleSet $ruleSet): void
    {
        $totalCount = count($ruleSet);
        $validCount = $context->getValidCounts($ruleSet);

        $valid = match ($ruleSet->operator) {
            OperationType::ALL => $validCount === $totalCount,
            OperationType::ANY => $validCount > 0,
            default => throw new \UnexpectedValueException("Unsupported operator: {$ruleSet->operator}"),
        };

        $context->addResult($ruleSet, new Result($valid));
    }
}
