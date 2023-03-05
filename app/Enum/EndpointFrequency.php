<?php

namespace App\Enum;

use Illuminate\Support\Str;

enum EndpointFrequency:int
{
    case FIVE_SECONDS = 5;
    case ONE_MINUTE = 60;
    case FIVE_MINUTES = 5*60;
    case THIRTY_MINUTES = 30 * 60;
    case ONE_HOUR = 60 * 60;
    case TWO_HOUR = 60 * 120;

    public static function getAllFrequencies()
    {
        return collect(self::cases())->map(function ($case){
           return $case->value;
        })->all();
    }

    public function getLabel()
    {
        return $this->label();
    }

    private function label()
    {
       return Str::of($this->name)->lower()->replace('_', ' ')->ucfirst();
    }

}
