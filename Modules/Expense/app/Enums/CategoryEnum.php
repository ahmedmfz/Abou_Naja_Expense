<?php

namespace Modules\Expense\Enums;

enum CategoryEnum  :  int{
    case TRAVEL        = 1;
    case FOOD          = 2;
    case UTILITIES     = 3;
    case RENT          = 4;
    case SUPPLIES      = 5;
    case ENTERTAINMENT = 6;
    case HEALTH        = 7;
    case EDUCATION     = 8;
    case TRANSPORT     = 9;
    case MISC          = 10;


    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }
    public function is(self $type): bool
    {
        return $this === $type;
    }

}

