<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ReelService;
use Illuminate\Http\Request;

class ManageSavedAudioController extends Controller
{
    protected $service;

    public function __construct(ReelService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Saved Audios';
        $savedAudios = $this->service->getSavedAudios(getPaginate());
        return view('admin.reels.saved_audios', compact('pageTitle', 'savedAudios'));
    }

    public function destroy($id)
    {
        $this->service->deleteSavedAudio($id);

        $notify[] = ['success', 'Saved audio entry removed successfully'];
        return back()->withNotify($notify);
    }
}
