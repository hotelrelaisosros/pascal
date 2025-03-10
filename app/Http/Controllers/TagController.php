<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  
     public function index()
     {  
        $tags = Tag::select(["id" ,"title"])->get();
         return view('tags.index' , ['tags'=>$tags]);
     }
 
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         return view('tags.create');
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
             'title'=> 'required', 
         ]);  

         Tag::insert($validated);
         return redirect()->route('tags.index');
     }
 
     /**
      * Display the specified resource.
      *
      * @param  \App\Models\Tag  $tag
      * @return \Illuminate\Http\Response
      */
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\Models\Tag  $tag
      * @return \Illuminate\Http\Response
      */
     public function edit(Tag $tag)
     {
        
         return view('tags.edit',['tag'=>$tag]);
     }
 
     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \App\Models\Tag  $tag
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, Tag $tag)
     {
       $validated = $request->validate([
             'title'=> 'required',
         ]); 
         $tag->update($validated);
         return redirect()->route('tags.index')->with('success', 'Tag updated successfully.');
     }
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  \App\Models\Tag  $tag
      * @return \Illuminate\Http\Response
      */
     public function destroy(Tag $tag)
     {
         $tag->delete();
         return redirect()->route('tags.index')->with('success', 'Tag deleted successfully.');
     }
}
