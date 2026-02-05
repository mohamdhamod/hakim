<?php

namespace App\Enums;


class ConfigurationsTypeEnum
{
    const CURRENCIES = "Currencies";
    const FEE_TYPES = "Fee types";
    const ALL = [
        self::CURRENCIES,
        self::FEE_TYPES
    ];


}
