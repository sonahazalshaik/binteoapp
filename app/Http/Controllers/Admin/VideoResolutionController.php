<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoResolution;
use Illuminate\Http\Request;
use App\Services\Admin\VideoService;

class VideoResolutionController extends Controller
{
    protected $service;

    public function __construct(VideoService $service)
    {
        $this->service = $service;
    }

    public function index(){
        $pageTitle = 'Video Resolution';
        $resolutions = VideoResolution::searchable(['resolution_label'])->paginate(getPaginate());
        return view('admin.video_resolution.index', compact('pageTitle','resolutions'));

    }

    public function save(Request $request, $id=0){
        $request->validate([
            'resolution_label' => 'required|string|unique:video_resolutions,resolution_label,'.$id,
            'width' => 'required|numeric|unique:video_resolutions,width,' . $id,
            'height' => 'required|numeric|unique:video_resolutions,height,' . $id,
        ]);

        $this->service->saveResolution($id ?: null, $request->only(['resolution_label', 'width', 'height']));

        $notify[] = ['success', $id ? 'Video resolution updated successfully' : 'Video resolution added successfully'];
        return back()->withNotify($notify);
    }

    public function status($id){
        return $this->service->toggleResolutionStatus($id);
    }

    public function destroy($id) {
        $this->service->deleteResolution($id);
        $notify[] = ['success', 'Resolution deleted successfully'];
        return back()->withNotify($notify);
    }

}
