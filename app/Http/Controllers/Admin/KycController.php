<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Lib\FormProcessor;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;

class KycController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function setting()
    {
        $pageTitle = 'KYC Setting';
        $form = $this->service->getKycForm();
        return view('admin.kyc.setting',compact('pageTitle','form'));
    }

    public function settingUpdate(Request $request)
    {
        $this->service->updateKycSetting($request);

        $notify[] = ['success','KYC data updated successfully'];
        return back()->withNotify($notify);
    }
}
