<?php

namespace App\Http\Controllers;

use App\Enum\EndpointFrequency;
use App\Models\EndPoint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EndpointController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'site_id' => ['required', Rule::exists('sites', 'id')],
            'frequency' => ['required', Rule::in(EndpointFrequency::getAllFrequencies())]
        ]);

        EndPoint::create($data + [
                'next_check' => now()->addSecond($data['frequency']),
                'location' => 'vietnam'
            ]);

    }
}
