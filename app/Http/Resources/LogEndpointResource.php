<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class LogEndpointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $statusText = Response::$statusTexts[$this->status_code] ?? '';
        return [
            'id' => $this->id,
            'last_checked' => DatetimeResource::make($this->created_at),
            'last_status' => $this->status_code.' '.$statusText,
            'is_success' => $this->is_success,
            'response_body' => !$this->isSuccess ? $this->response_body : ''
        ];
    }
}
