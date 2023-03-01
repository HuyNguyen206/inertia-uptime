<?php

namespace App\Http\Resources;

use App\Enum\EndpointFrequency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EndpointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'location' => $this->location,
            'frequency' => EndpointFrequency::from($this->frequency)->getLabel(),
            'frequency_value' => $this->frequency,
            'lastest_log' => LogEndpointResource::make($this->latestLog),
            'up_time' => $this->upTimePercentage(),
            'full_url' => $this->fullUrl()
        ];
    }
}
