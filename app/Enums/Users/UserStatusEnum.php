<?php

namespace App\Enums\Users;

enum UserStatusEnum: string
{
    case BANNED = 'banned';

    case ACTIVE = 'active';
}
