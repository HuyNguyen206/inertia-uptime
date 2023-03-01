<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteStoreRequest;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteStoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SiteStoreRequest $request)
    {
        $site = $request->user()->sites()->create($request->validated());

        return redirect(route('dashboard', $site));
    }

    public function addEmailNotification(Request $request, Site $site)
    {
        //authorize
        $this->authorize('canAddEmailNotification', $site);
        //validation
        $request->validate([
            'email' => 'required|email'
        ]);
        if (is_array($site->email_notification_list)) {
            $emailList = $site->email_notification_list;
            $emailList[] = $request->email;
            $site->email_notification_list = $emailList;
        } else {
            $site->email_notification_list = [$request->email];
        }
        $site->save();
    }
}
