<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::withCount(['videos', 'reels'])->searchable(['name'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function findCategory($id): Category
    {
        return Category::findOrFail($id);
    }

    public function saveCategory(Request $request, $id = 0): Category
    {
        if ($id) {
            $category = Category::findOrFail($id);
        } else {
            $category = new Category();
        }

        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->icon = $request->icon;
        $category->save();

        return $category;
    }

    public function toggleStatus($id): mixed
    {
        return Category::changeStatus($id);
    }

    public function checkSlug($slug): array
    {
        return [
            'exists' => Category::where('slug', $slug)->exists(),
        ];
    }

    public function deleteCategory($id): void
    {
        Category::findOrFail($id)->delete();
    }
}
