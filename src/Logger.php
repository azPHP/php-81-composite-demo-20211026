<?php

declare(strict_types=1);

namespace AzPhp\Demo20211026;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class Logger implements LoggerInterface
{
    use LoggerTrait;

    public function log($level, $message, array $context = array())
    {
        fprintf(STDERR, "[%s] %s\n%s\n", $level, $message, json_encode($context));
    }
}
