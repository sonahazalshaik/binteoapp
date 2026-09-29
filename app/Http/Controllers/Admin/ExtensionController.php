<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Extension;
use App\Services\Admin\SettingService;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
    protected $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $pageTitle  = 'Extensions';
        $extensions = $this->service->getExtensions();
        return view('admin.extension.index', compact('pageTitle', 'extensions'));
    }

    public function update(Request $request, $id)
    {
        $extension = Extension::findOrFail($id);
        $validationRule = [];
        foreach ($extension->shortcode as $key => $val) {
            $validationRule = array_merge($validationRule,[$key => 'required']);
        }
        $request->validate($validationRule);

        $extension = $this->service->updateExtension($id, $request->all());

        $notify[] = ['success', $extension->name . ' updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return $this->service->toggleExtensionStatus($id);
    }
}
