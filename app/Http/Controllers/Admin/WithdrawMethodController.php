<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\RequiredConfig;
use App\Models\WithdrawMethod;
use App\Rules\FileTypeValidate;
use App\Services\Admin\GatewayService;
use Illuminate\Http\Request;

class WithdrawMethodController extends Controller
{
    protected $service;

    public function __construct(GatewayService $service)
    {
        $this->service = $service;
    }
    public function methods()
    {
        $pageTitle = 'Withdrawal Methods';
        $methods = $this->service->getWithdrawMethods();
        return view('admin.withdraw.index', compact('pageTitle', 'methods'));
    }

    public function create()
    {
        $pageTitle = 'New Withdrawal Method';
        return view('admin.withdraw.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        try {
            $method = $this->service->createWithdrawMethod($request);
        } catch (\Illuminate\Validation\ValidationException $exp) {
            throw $exp;
        } catch (\Exception $exp) {
            \Log::error('Withdraw method store failed: ' . $exp->getMessage());
            $notify[] = ['errors', 'Image could not be uploaded: ' . $exp->getMessage()];
            return back()->withNotify($notify)->withInput();
        }

        $notify[] = ['success', 'Withdrawal method added successfully'];
        return to_route('admin.withdraw.method.index')->withNotify($notify);
    }


    public function edit($id)
    {
        $pageTitle = 'Update Withdrawal Method';
        $method = $this->service->getWithdrawMethodForEdit($id);
        $form = $method->form;
        return view('admin.withdraw.edit', compact('pageTitle', 'method','form'));
    }

    public function update(Request $request, $id)
    {
        try {
            $this->service->updateWithdrawMethod($request, $id);
        } catch (\Illuminate\Validation\ValidationException $exp) {
            throw $exp;
        } catch (\Exception $exp) {
            \Log::error('Withdraw method update failed: ' . $exp->getMessage());
            $notify[] = ['errors', 'Image could not be uploaded: ' . $exp->getMessage()];
            return back()->withNotify($notify)->withInput();
        }

        $notify[] = ['success', 'Withdrawal method updated successfully'];
        return back()->withNotify($notify);
    }


    public function status($id)
    {
        return $this->service->toggleWithdrawMethodStatus($id);
    }

    public function destroy($id) {
        $this->service->deleteWithdrawMethod($id);
        $notify[] = ['success', 'Withdrawal method deleted successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Stream the method logo straight from R2 (header auth).
     * The bucket has no public access and presigned query URLs are
     * rejected by this R2 endpoint, so browsers load it via this proxy.
     */
    public function image($id)
    {
        $method = WithdrawMethod::findOrFail($id);
        $key = $this->r2Key($method->image);
        abort_unless($key && \Illuminate\Support\Facades\Storage::disk('r2')->exists($key), 404);
        return \Illuminate\Support\Facades\Storage::disk('r2')->response($key);
    }

    /**
     * Resolve the R2 object key from a stored full URL or plain key.
     */
    protected function r2Key($path): ?string
    {
        if (!$path) return null;
        if (strpos($path, 'http') !== 0) return ltrim($path, '/');

        $r2 = gs('cloudflare_config');
        $endpoint = @$r2->endpoint ?: config('filesystems.disks.r2.endpoint');
        $bucket = @$r2->bucket ?: config('filesystems.disks.r2.bucket');
        $customUrl = @$r2->url ?: config('filesystems.disks.r2.url');

        if ($customUrl && strpos($path, $customUrl) !== false) {
            return ltrim(str_replace(rtrim($customUrl, '/') . '/', '', $path), '/') ?: null;
        }
        if ($endpoint && $bucket) {
            $base = rtrim($endpoint, '/') . '/' . $bucket . '/';
            if (strpos($path, $base) !== false) {
                return ltrim(str_replace($base, '', $path), '/') ?: null;
            }
        }
        return null;
    }

}
