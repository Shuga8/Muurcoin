<?php

namespace App\Enums\Transactions;

enum TransactionStatusEnum: string
{

    case PENDING = 'pending';

    case COMPLETED = 'completed';

    case FAILED = 'failed';
}
