<?php

namespace App\Http\Controllers;

use App\Models\RecipeCategory;
use Illuminate\Http\Request;

class RecipeCategoryController extends Controller
{
    public function index()
    {
        $categories = RecipeCategory::orderBy('name')->get();
        return view('frontend.recipe_categories.index',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name'=>'required|string|max:255|unique:recipe_categories,name']);
        RecipeCategory::create(['name'=>$request->name]);
        return back()->with('success','Category added!');
    }

    public function edit(RecipeCategory $recipeCategory)
    {
        $categories = RecipeCategory::orderBy('name')->get();
        return view('frontend.recipe_categories.create',[
            'category'=>$recipeCategory,
            'categories'=>$categories
        ]);
    }

    public function update(Request $request, RecipeCategory $recipeCategory)
    {
        $request->validate(['name'=>'required|string|max:255|unique:recipe_categories,name,'.$recipeCategory->id]);
        $recipeCategory->update(['name'=>$request->name]);
        return redirect()->route('recipe-categories.index')
                         ->with('success','Category updated!');
    }

    public function destroy(RecipeCategory $recipeCategory)
    {
        $recipeCategory->delete();
        return back()->with('success','Category deleted!');
    }
}
