<?php

namespace App\Enum;

enum AccountType : string
{
    case CURRENT = 'current';

    case MORTGAGE = 'mortgage';

    case SAVINGS = 'savings';

}
