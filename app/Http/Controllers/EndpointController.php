<?php

namespace App\Http\Controllers;

use App\Enum\EndpointFrequency;
use App\Http\Requests\EndpointStoreRequest;
use App\Http\Resources\EndpointResource;
use App\Http\Resources\LogEndpointResource;
use App\Http\Resources\SiteResource;
use App\Models\EndPoint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class EndpointController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function store(EndpointStoreRequest $request)
    {
        $data = $request->validated();
        $urlData = parse_url($data['location']);
        $data['location'] = '/' . trim($urlData['path'], '/');
        if (isset($urlData['query'])) {
            $data['location'] .= '?'.$urlData['query'];
        }
        EndPoint::create($data + [
                'next_check' => now()->addSecond($data['frequency']),
            ]);

    }

    public function destroy(EndPoint $endpoint)
    {
        $this->authorize('delete', $endpoint->site);
        $endpoint->delete();
    }

    public function update(Request $request, EndPoint $endpoint)
    {
        $this->authorize('update', $endpoint->site);
        $data = $request->validate([
            'location' => 'required',
            'frequency' => ['required', new Enum(EndpointFrequency::class)]
        ]);
        $endpoint->update($data);
    }

    public function getLogs(Request $request, EndPoint $endpoint)
    {
        $this->authorize('canGetLogs', $endpoint->site);
        return Inertia::render('EndpointLog', [
            'sites' => SiteResource::collection($request->user()->sites),
            'logs' => LogEndpointResource::collection($endpoint->logs()->paginate(10)),
            'endpoint' => EndpointResource::make($endpoint)
        ]);
    }
}
