<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use Category;
use Common;
use CategoryCollection;
use CategoryResource;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $categories->each(function ($categories) {
            $categories->entity;
        });
        return new CategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entity = Common::findOrCreateEntity($request->entity);
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'entity_id' => $entity->id,
        ]);
        return response()->Json(['msg' => "Categoria registada com sucesso"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Helpers\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category = Category::findOrfail($category);
        dd($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Helpers\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category = Category::findOrfail($category);
        // $category->update([
        //     'name' => $request->name,
        //     'description' => $request->description,
        //     'entity_id' => $request->entity,
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Helpers\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrfail($id);
        if ($category->posts) {
            return response()->Json([
                'msg' => "Categoria vinculada à publicação, não pode ser eliminada",
                'can' => false
            ], 200);
        }

        $category->delete();
        return response()->Json([
            'msg' => "Categoria eliminada com sucesso",
            'can' => true

        ], 200);
    }
}
