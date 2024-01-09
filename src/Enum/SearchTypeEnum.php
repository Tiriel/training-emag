<?php

namespace App\Enum;

enum SearchTypeEnum
{
    case Id;
    case Title;

    public function getQueryParam(): string
    {
        return match ($this) {
            self::Id => 'i',
            self::Title => 't',
        };
    }
}
