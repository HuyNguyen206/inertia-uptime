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

    public function fullUrl()
    {
        return sprintf('%s://%s%s', $this->site->scheme, $this->site->domain, $this->location);
    }

    public function logs()
    {
        return $this->hasMany(EndpointLog::class, 'endpoint_id');
    }

    public function latestLog()
    {
        return $this->hasOne(EndpointLog::class, 'endpoint_id')->latestOfMany()->withDefault();
    }

    public function upTimePercentage()
    {
        if ($this->total_logs_count) {
            return number_format(($this->success_logs_count/$this->total_logs_count) * 100, 2). '%';
        }
    }
}
