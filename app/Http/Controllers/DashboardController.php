<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteResource;
use App\Models\Site;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Site $site = null)
    {
        if ($site) {
            $request->user()->sites()->default()->update(['is_default' => false]);
            $site->update(['is_default' => true]);
        } else {
            $site = Site::where('is_default', true)->first() ?? Site::first();
        }

        return Inertia::render('Dashboard', [
            'site' => SiteResource::make($site),
            'sites' => SiteResource::collection(Site::all())
        ]);
    }
}
