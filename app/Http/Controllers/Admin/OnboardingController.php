<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnboardingSlide;
use App\Services\Admin\ContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OnboardingController extends Controller
{
    protected $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->service->getOnboardingSlides();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return view('admin.onboarding-slides.actions', compact('row'));
            });

            $table->editColumn('id', function ($row) {
                return $row->id;
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('image_path', function ($row) {
                if ($row->image_path) {
                    $url = $row->image_url;
                    return '<img src="' . $url . '" width="70" height="70" class="img-thumbnail" style="object-fit: contain; background-color: #f8f9fa;">';
                }
                return '';
            });
            $table->editColumn('sort_order', function ($row) {
                return $row->sort_order;
            });

            $table->rawColumns(['actions', 'placeholder', 'image_path']);

            return $table->make(true);
        }

        return view('admin.onboarding-slides.index');
    }

    public function create()
    {
        if (OnboardingSlide::count() >= 3) {
            return redirect()->route('admin.onboarding-slides.index')
                ->with('error', 'Maximum limit of 3 onboarding slides has been reached. Please edit or delete existing slides.');
        }

        return view('admin.onboarding-slides.create');
    }

    public function store(Request $request)
    {
        try {
            $this->service->createOnboardingSlide(
                $request->except('image'),
                $request->file('image')
            );
        } catch (\Exception $e) {
            return redirect()->route('admin.onboarding-slides.index')
                ->with('error', $e->getMessage());
        }

        return redirect()->route('admin.onboarding-slides.index')->with('success', 'Onboarding slide created successfully.');
    }

    public function edit($id)
    {
        $onboardingSlide = OnboardingSlide::findOrFail($id);
        return view('admin.onboarding-slides.edit', compact('onboardingSlide'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:60',
            'description' => 'required|string|max:280',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        $this->service->updateOnboardingSlide($id, $request->except('image'), $request->file('image'));

        return redirect()->route('admin.onboarding-slides.index')->with('success', 'Onboarding slide updated successfully.');
    }

    public function show($id)
    {
        $onboardingSlide = OnboardingSlide::findOrFail($id);
        return view('admin.onboarding-slides.show', compact('onboardingSlide'));
    }

    public function destroy($id)
    {
        $this->service->deleteOnboardingSlide($id);

        return back()->with('success', 'Onboarding slide deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        $this->service->massDeleteOnboardingSlides(request('ids'));

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
