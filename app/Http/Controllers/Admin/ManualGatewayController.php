<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\RequiredConfig;
use App\Rules\FileTypeValidate;
use App\Services\Admin\GatewayService;
use Illuminate\Http\Request;

class ManualGatewayController extends Controller
{
    protected $service;

    public function __construct(GatewayService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $pageTitle = 'Manual Gateways';
        $gateways = $this->service->getManualGateways();
        return view('admin.gateways.manual.list', compact('pageTitle', 'gateways'));
    }

    public function create()
    {
        $pageTitle = 'Add Manual Gateway';
        return view('admin.gateways.manual.create', compact('pageTitle'));
    }


    public function store(Request $request)
    {
        try {
            $method = $this->service->createManualGateway($request);
        } catch (\Exception $exp) {
            $notify[] = ['errors', 'Image could not be uploaded'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', $method->name . ' Manual gateway has been added.'];
        return back()->withNotify($notify);
    }

    public function edit($alias)
    {
        $pageTitle = 'Edit Manual Gateway';
        $method = $this->service->getManualGatewayForEdit($alias);
        $form = $method->form;
        return view('admin.gateways.manual.edit', compact('pageTitle', 'method','form'));
    }

    public function update(Request $request, $code)
    {
        try {
            $method = $this->service->updateManualGateway($request, $code);
        } catch (\Exception $exp) {
            $notify[] = ['errors', 'Image could not be uploaded'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', $method->name . ' manual gateway updated successfully'];
        return to_route('admin.gateway.manual.edit',[$method->alias])->withNotify($notify);
    }

    private function validation($request,$formProcessor,$isUpdate = false)
    {
        $validation = [
            'name'           => 'required',
            'rate'           => 'required|numeric|gt:0',
            'currency'       => 'required',
            'min_limit'      => 'required|numeric|gt:0',
            'max_limit'      => 'required|numeric|gt:min_limit',
            'fixed_charge'   => 'required|numeric|gte:0',
            'percent_charge' => 'required|numeric|between:0,100',
            'image' => [$isUpdate ? 'nullable' : 'required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'instruction'    => 'required'
        ];

        $generatorValidation = $formProcessor->generatorValidation();
        $validation = array_merge($validation,$generatorValidation['rules']);
        $request->validate($validation,$generatorValidation['messages']);
    }

    public function status($id)
    {
        return $this->service->toggleGatewayStatus($id);
    }
}
