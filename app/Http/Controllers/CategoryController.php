<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $categories = Category::select(["id", "title"])->get();
        return view('category.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
        ]);

        Category::insert($validated);
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $tag
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('category.edit', ['categories' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'title' => 'required',
        ]);
        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->blog()->exists()) {
            return redirect()->route('categories.index')->with('error', 'Category cannot be deleted with blogs');
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
