<?php

namespace App\Enums;


class ActionEnum
{
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

    const ALL = [
        self::CREATE,
        self::UPDATE,
        self::DELETE,
    ];
}
