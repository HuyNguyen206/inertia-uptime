<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteStoreRequest;

class SiteStoreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(SiteStoreRequest $request)
    {
        $site = $request->user()->sites()->create($request->validated());

        return redirect(route('dashboard', $site));
    }
}
