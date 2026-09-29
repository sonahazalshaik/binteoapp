<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Services\Admin\StorageService;
use Illuminate\Http\Request;

class ManageStorageController extends Controller {
    protected $service;

    public function __construct(StorageService $service)
    {
        $this->service = $service;
    }

    public function index() {
        $pageTitle = 'Manage Storage';
        $storages  = $this->service->getAllStorages();
        return view('admin.storage.index', compact('pageTitle', 'storages'));
    }

    public function wasabiForm($id = null) {
        $pageTitle = $id ? 'Edit Wasabi Storage ' : 'Create New Wasabi Storage';
        $wasabi    = $this->service->getStorageForm(Status::WASABI_SERVER, $id);

        return view('admin.storage.wasabi.form', compact('pageTitle', 'wasabi'));
    }

    public function digitalOceanForm($id = null) {
        $pageTitle    = $id ? 'Edit digital Ocean Storage ' : 'Create New Digital Ocean Storage';
        $digitalOcean = $this->service->getStorageForm(Status::DIGITAL_OCEAN_SERVER, $id);

        return view('admin.storage.digital_ocean.form', compact('pageTitle', 'digitalOcean'));
    }

    public function ftpForm($id = null) {
        $pageTitle = $id ? 'Edit Ftp Storage ' : 'Create Ftp Storage';
        $ftp       = $this->service->getStorageForm(Status::FTP_SERVER, $id);

        return view('admin.storage.ftp.form', compact('pageTitle', 'ftp'));
    }

    public function saveWasabi(Request $request, $id = null) {
        $this->service->saveWasabi($request, $id);
        $notify[] = ['success', $id ? 'Wasabi storage has been updated successfully' : 'Wasabi storage has been created successfully'];
        return redirect()->route('admin.storage.index')->withNotify($notify);
    }

    public function saveDigitalOcean(Request $request, $id = null) {
        $this->service->saveDigitalOcean($request, $id);
        $notify[] = ['success', $id ? 'Digital ocean storage has been updated successfully' : 'Digital ocean storage has been created successfully'];
        return redirect()->route('admin.storage.index')->withNotify($notify);
    }

    public function saveFtp(Request $request, $id = null) {
        $this->service->saveFtp($request, $id);
        $notify[] = ['success', $id ? 'Ftp storage has been updated successfully' : 'Ftp storage has been created successfully'];
        return redirect()->route('admin.storage.index')->withNotify($notify);
    }

    public function checkConfig($id) {
        $result = $this->service->checkConfig($id);
        return response()->json($result);
    }

    public function status($id) {
        return $this->service->toggleStatus($id);
    }
}
