<?php

namespace App\Enum;

enum UserState: string
{
    case New = 'New';
    case Active = 'Active';
    case Blocked = 'Blocked';
    case Banned = 'Banned';
}