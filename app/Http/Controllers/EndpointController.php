<?php

namespace App\Http\Controllers;

use App\Http\Requests\EndpointStoreRequest;
use App\Models\EndPoint;

class EndpointController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EndpointStoreRequest $request)
    {
      $data = $request->validated();

        EndPoint::create($data + [
                'next_check' => now()->addSecond($data['frequency']),
            ]);

    }
}
