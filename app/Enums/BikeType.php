<?php

namespace App\Enums;

class BikeType
{
    const ECO150 = 'Eco 150';
    const CARGO = 'Eco 150 Cargo';
    const KARIZMA= 'Karizma';

    public static function all()
    {
        return [
            self::ECO150,
            self::CARGO,
            self::KARIZMA,
        ];
    }
}
