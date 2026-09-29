<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Rules\FileTypeValidate;
use App\Services\Admin\ContentService;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    protected $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    public function index(){
        $pageTitle = 'Manage Frontend Content';
        return view('admin.frontend.index', compact('pageTitle'));
    }

    public function templates()
    {
        abort(404);
        $pageTitle = 'Templates';
        $temPaths = array_filter(glob('core/resources/views/templates/*'), 'is_dir');
        foreach ($temPaths as $key => $temp) {
            $arr = explode('/', $temp);
            $tempname = end($arr);
            $templates[$key]['name'] = $tempname;
            $templates[$key]['image'] = asset($temp) . '/preview.jpg';
        }
        $extraTemplates = json_decode(getTemplates(), true);
        return view('admin.frontend.templates', compact('pageTitle', 'templates', 'extraTemplates'));

    }
    public function templatesActive(Request $request)
    {
        $this->service->activateTemplate($request->name);

        $notify[] = ['success', strtoupper($request->name).' template activated successfully'];
        return back()->withNotify($notify);
    }

    public function seoEdit()
    {
        $pageTitle = 'SEO Configuration';
        $seo = $this->service->getOrCreateSeoData();
        return view('admin.frontend.seo', compact('pageTitle', 'seo'));
    }

    public function frontendSections($key)
    {
        $section = @getPageSections()->$key;
        abort_if(!$section || !$section->builder,404);
        $data = $this->service->getFrontendSections($key);
        $pageTitle = $section->name ;
        return view('admin.frontend.section', array_merge(compact('key', 'pageTitle'), $data));
    }

    public function frontendContent(Request $request, $key)
    {
        try {
            $content = $this->service->saveFrontendContent($request, $key);
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Couldn\'t upload the image'];
            return back()->withNotify($notify);
        }

        if (!$request->id && (isset(getPageSections()->$key->element->seo) && getPageSections()->$key->element->seo) && $request->type != 'content') {
            $notify[] = ['info','Configure SEO content for ranking'];
            $notify[] = ['success', 'Content updated successfully'];
            return to_route('admin.frontend.sections.element.seo',[$key,$content->id])->withNotify($notify);
        }

        $notify[] = ['success', 'Content updated successfully'];
        return back()->withNotify($notify);
    }

    public function frontendElement($key, $id = null)
    {
        $section = @getPageSections()->$key;
        if (!$section) {
            return abort(404);
        }

        unset($section->element->modal);
        unset($section->element->seo);
        $pageTitle = $section->name . ' Items';
        $data = $this->service->getFrontendElement($key, $id);
        return view('admin.frontend.element', compact('section', 'key', 'pageTitle', 'data'));
    }

    public function frontendElementSlugCheck($key,$id = null){
        $exist = $this->service->checkFrontendSlug($key, $id);
        return response()->json([
            'exists'=>$exist
        ]);
    }

    public function frontendSeo($key,$id)
    {
        $hasSeo = @getPageSections()->$key->element->seo;
        if (!$hasSeo) {
            abort(404);
        }
        $data = $this->service->getFrontendElement($key, $id);
        $pageTitle = 'SEO Configuration';
        return view('admin.frontend.frontend_seo', compact('pageTitle','key','data'));
    }

    public function frontendSeoUpdate(Request $request, $key,$id){
        try {
            $this->service->updateFrontendSeo($request, $key, $id);
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Couldn\'t upload the image'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'SEO content updated successfully'];
        return back()->withNotify($notify);
    }

    public function remove($id)
    {
        $this->service->removeFrontendContent($id);
        $notify[] = ['success', 'Content removed successfully'];
        return back()->withNotify($notify);
    }



}
