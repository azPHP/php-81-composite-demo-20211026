<?php 

declare(strict_types=1);

// This file is the driver/client to demonstrate running the code.

require __DIR__ . '/vendor/autoload.php';

// Depending on version of Composer, you may need to manually include enums.
//require_once __DIR__ . '/src/OperationType.php';
//require_once __DIR__ . '/src/RecordType.php';

use AzPhp\Demo20211026\{DnsResolver, Logger, RuleSet, Validator};

// Create the Validator instance. Normally, you'd probably get this from a DIC (Dependency Injection Container).
$resolver = new DnsResolver(/* Might have its own dependencies, normally. */);
$logger = new Logger(/* Might have its own dependencies, normally. */);
$validator = new Validator($resolver, $logger);

// Hydrate the rules into an object.
// Note: Rules normally would be fetched from the DB.
$rules = [
    'operator' => 'all',
    'rules' => [
        [
            'operator' => 'any',
            'rules' => [
                ['prefix' => '.', 'type' => 'NS', 'expectedValue' => 'ns1.example.com'],
                ['prefix' => '.', 'type' => 'NS', 'expectedValue' => 'ns2.example.com'],
                ['prefix' => '.', 'type' => 'NS', 'expectedValue' => 'ns3.example.com'],
                ['prefix' => '.', 'type' => 'NS', 'expectedValue' => 'ns4.example.com']
            ],
        ],
        ['prefix' => '.', 'type' => 'A', 'expectedValue' => '111.111.111.111'],
        [
            'operator' => 'any',
            'rules' => [
                ['prefix' => 'foo', 'type' => 'CNAME', 'expectedValue' => '222.222.222.222'],
                ['prefix' => 'bar', 'type' => 'CNAME', 'expectedValue' => '222.222.222.222']
            ],
        ]
    ],
];
$ruleSet = RuleSet::fromArray($rules);

// Validate the ruleset with the validator.
$validatedRuleSet = $validator->validate('example.com', $ruleSet);

// Output results for purposes of the demo.
// Note: Validated rules normally would be saved back to the DB.
echo ($validatedRuleSet->valid ? "VALID\n" : "INVALID\n") . "-----\n";
print_r($validatedRuleSet->toArray());
