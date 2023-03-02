<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Site extends Model
{
    use HasFactory;

    protected $casts = [
        'email_notification_list' => 'array'
    ];

    protected static function booted()
    {
       static::creating(function (Site $site){
           info('test create site');
           $origin = $site->domain;
           $site->scheme = Str::before($origin, '://');
           $site->domain = Str::after($origin, '://');
       });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function endpoints()
    {
        return $this->hasMany(EndPoint::class);
    }

    public function scopeDefault(Builder $builder)
    {
        $builder->where('is_default', true);
    }
}
