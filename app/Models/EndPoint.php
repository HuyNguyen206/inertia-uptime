<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndPoint extends Model
{
    use HasFactory;
    protected $casts = [
        'next_check' => 'datetime'
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
