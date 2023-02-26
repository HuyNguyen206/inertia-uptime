<?php

namespace App\Http\Requests;

use App\Enum\EndpointFrequency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class EndpointStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->sites()->where('sites.id', $this->site_id)->exist();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'site_id' => ['required', Rule::exists('sites', 'id')],
            'frequency' => ['required', new Enum(EndpointFrequency::class)],
            'location' => ['required', 'string', Rule::unique('end_points')]
        ];
    }
}
