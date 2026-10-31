<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::with('parent')->withCount('products')->orderBy('position')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new Category(['is_active' => true]),
            'parents' => Category::whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create([...$request->validated(), 'position' => $request->integer('position')]);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria creata.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', [
            'category' => $category,
            'parents' => Category::whereNull('parent_id')->whereKeyNot($category->id)->orderBy('name')->get(),
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update([...$request->validated(), 'position' => $request->integer('position')]);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria aggiornata.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete(); // i prodotti restano, con category_id = null

        return redirect()->route('admin.categories.index')->with('success', 'Categoria eliminata.');
    }
}
