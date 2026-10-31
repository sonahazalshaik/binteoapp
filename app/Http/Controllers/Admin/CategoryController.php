<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index() {
        $pageTitle  = "Categories";
        $categories = $this->service->getAllCategories();
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    public function create() {
        $pageTitle = "Add Category";
        $materialIcons = json_decode(file_get_contents(resource_path('views/admin/partials/material_icons.json')), true) ?? [];
        return view('admin.category.create', compact('pageTitle', 'materialIcons'));
    }

    public function edit($id) {
        $category = $this->service->findCategory($id);
        $pageTitle = "Edit Category";
        $materialIcons = json_decode(file_get_contents(resource_path('views/admin/partials/material_icons.json')), true) ?? [];
        if ($category->icon && !in_array($category->icon, $materialIcons)) {
            array_unshift($materialIcons, $category->icon);
        }
        return view('admin.category.edit', compact('pageTitle', 'category', 'materialIcons'));
    }

    public function save(Request $request, $id = 0) {
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id,
            'slug' => 'required|string|unique:categories,slug,' . $id,
            'icon' => 'nullable',
        ]);

        $this->service->saveCategory($request, $id);

        $notify[] = ['success', $id ? 'Category updated successfully' : 'Category added successfully'];
        return back()->withNotify($notify);
    }

    public function status($id) {
        return $this->service->toggleStatus($id);
    }

    public function checkSlug() {
        return response()->json($this->service->checkSlug(request()->slug));
    }

    public function destroy($id) {
        $this->service->deleteCategory($id);
        $notify[] = ['success', 'Category deleted successfully'];
        return back()->withNotify($notify);
    }
}
