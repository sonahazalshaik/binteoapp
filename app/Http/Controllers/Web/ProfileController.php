<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\Frontend\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function edit(Request $request): View
    {
        $data = $this->profileService->editData($request);

        return view('frontend.profile.edit', $data);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $result = $this->profileService->update($request, $user);

        if (!$result['success']) {
            $notify[] = ['error', $result['message']];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', $result['message']];
        return Redirect::route('profile.edit')->withNotify($notify);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->profileService->destroy($request);

        $notify[] = ['success', 'Your request for account deletion has been submitted to the administrator for approval.'];
        return Redirect::to('/')->withNotify($notify);
    }
}
