<?php

namespace App\Http\Controllers;

use App\Http\Resources\EndpointResource;
use App\Http\Resources\SiteResource;
use App\Models\Site;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Site|null $site = null)
    {
        if ($site) {
            $request->user()->sites()->default()->update(['is_default' => false]);
            $site->update(['is_default' => true]);
        } else {
            $site = Site::where('is_default', true)->first() ?? Site::first();
        }

        return Inertia::render('Dashboard', [
            'site' => !$site ? null : SiteResource::make($site),
            'endpoints' => !$site ? [] : EndpointResource::collection(
                $site->endpoints
                    ->load(['site','latestLog'])
                    ->loadCount([
                        'logs as total_logs_count',
                        'logs as success_logs_count' => function(Builder $builder){
                            $builder->where('status_code', '>=', 200)
                                    ->where('status_code', '<', 300);
                    }])->sortByDesc('created_at')
            )
        ]);
    }
}
