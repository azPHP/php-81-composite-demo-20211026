<?php

declare(strict_types=1);

namespace AzPhp\Demo20211026;

enum RecordType: string
{
    case NS = 'NS';
    case A = 'A';
    case CNAME = 'CNAME';
}
