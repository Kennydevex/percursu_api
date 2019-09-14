<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Tag;
use TagCollection;
use TagResource;
use Illuminate\Http\Request;


class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return new TagCollection($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = Tag::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response()->Json(['msg' => "Marcador Registado com sucesso"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Helpers\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $tag = Tag::findOrfail($tag);
        dd($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Helpers\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $tag = Tag::findOrfail($tag);
        // $tag->update([
        //     'name' => $request->name,
        //     'description' => $request->description,
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Helpers\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::findOrfail($id);
        if (count($tag->posts) > 0) {
            return response()->Json([
                'msg' => "Marcador vinculado à publicação, não pode ser eliminado",
                'can' => false
            ], 200);
        }

        $tag->delete();
        return response()->Json([
            'msg' => "Marcador eliminado com sucesso",
            'can' => true

        ], 200);
    }
}
