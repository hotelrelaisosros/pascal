<?php

namespace App\Enums;

class TalkType
{
    const LIGHTNING = 'lightning';
    const STANDARD = 'standard';
    const KEYNOTE = 'keynote';

    public static function all()
    {
        return [
            self::LIGHTNING,
            self::STANDARD,
            self::KEYNOTE,
        ];
    }
}
