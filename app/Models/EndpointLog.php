<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndpointLog extends Model
{
    use HasFactory;

    protected $appends = ['is_success'];

    public function isSuccess(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status_code >= 200 && $this->status_code < 300);
    }

}
