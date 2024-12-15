<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Cache::remember('categories', now()->addMinutes(60), function () {
            return Category::all();
        });

        return view('category', compact('category'));
    }

    public function create()
    {
        return view('addcategory');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);

        Category::create($data);

        Cache::forget('categories');

        Cache::remember('categories', now()->addMinutes(60), function () {
            return Category::all();
        });

        return redirect(route('category'))->with('success', 'Category successfully created!');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        
        return view('editcategory', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $data = $request->only(['name']);
        Category::where('id', $id)->update($data);

        Cache::forget('categories');

        Cache::remember('categories', now()->addMinutes(60), function () {
            return Category::all();
        });

        return redirect(route('category'))->with('success', 'Category successfully updated!');
    }

    public function destroy($id)
    {
        Category::destroy($id);

        Cache::forget('categories');

        return redirect(route('category'))->with('success', 'Category successfully deleted!');
    }
}
