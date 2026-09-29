<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Rules\FileTypeValidate;
use App\Services\Admin\ContentService;
use Illuminate\Http\Request;

class PageBuilderController extends Controller
{
    protected $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    public function managePages()
    {
        $pData = $this->service->getPages();
        $pageTitle = 'Manage Pages';
        return view('admin.frontend.builder.pages', compact('pageTitle','pData'));
    }

    public function managePagesSave(Request $request){
        $request->validate([
            'name' => 'required|min:3|string|max:40',
            'slug' => 'required|min:3|string|max:40',
        ]);

        try {
            $this->service->createPage($request->only('name', 'slug'));
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'New page added successfully'];
        return back()->withNotify($notify);
    }

    public function managePagesUpdate(Request $request){
        $request->validate([
            'name' => 'required|min:3|string|max:40',
            'slug' => 'required|min:3|string|max:40'
        ]);

        try {
            $this->service->updatePage($request->only('id', 'name', 'slug'));
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Page updated successfully'];
        return back()->withNotify($notify);
    }

    public function checkSlug($id = null){
        $exist = $this->service->checkPageSlug(request()->slug, $id);
        return response()->json([
            'exists'=>$exist
        ]);
    }

    public function managePagesDelete($id){
        $this->service->deletePage($id);
        $notify[] = ['success', 'Page deleted successfully'];
        return back()->withNotify($notify);
    }

    public function manageSection($id)
    {
        $pData = Page::findOrFail($id);
        $pageTitle = 'Manage Section of '.$pData->name;
        $sections =  getPageSections(true);
        return view('admin.frontend.builder.index', compact('pageTitle','pData','sections'));
    }

    public function manageSectionUpdate($id, Request $request)
    {
        $request->validate([
            'secs' => 'nullable|array',
        ]);

        $this->service->updatePageSections($id, $request->secs);
        $notify[] = ['success', 'Page sections updated successfully'];
        return back()->withNotify($notify);
    }

    public function manageSeo($id){
        $page = Page::findOrFail($id);
        $pageTitle = 'SEO Configuration for '.$page->name .' Page';
        return view('admin.frontend.builder.seo', compact('pageTitle','page'));
    }

    public function manageSeoStore(Request $request, $id){
        try {
            $this->service->updatePageSeo($request, $id);
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Couldn\'t upload the image'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'SEO content updated successfully'];
        return back()->withNotify($notify);
    }
}
