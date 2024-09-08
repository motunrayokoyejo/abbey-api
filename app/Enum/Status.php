<?php

namespace App\Enum;

enum Status : string
{
    case PENDING = 'pending';

    case ACCEPTED = 'accepted';

    case DECLINED = 'declined';

    case BLOCKED = 'blocked';

}
