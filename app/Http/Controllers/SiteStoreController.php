<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteStoreRequest;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

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
            'email' => ['required','email', Rule::notIn($site->email_notification_list ?? [])]
        ], ['email.not_in' => 'The :attribute has already been taken.']);

        if (is_array($site->email_notification_list)) {
            $emailList = $site->email_notification_list;
            $site->email_notification_list = Arr::prepend($emailList, $request->email);
        } else {
            $site->email_notification_list = [$request->email];
        }
        $site->save();
    }

    public function removeEmailNotification(Request $request, Site $site, string $email)
    {
        $request->validate([
           'email' => ['email', Rule::in($site->email_notification_list ?? [])]
        ]);

        $this->authorize('canRemoveEmailNotification', $site);
        $emailList = $site->email_notification_list;
        $site->email_notification_list = collect($emailList)->filter(fn($emailCheck) => $emailCheck !== $email)->all();
        $site->save();
    }
}
