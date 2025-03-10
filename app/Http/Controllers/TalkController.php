<?php

namespace App\Http\Controllers;

use App\Models\Talk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TalkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('talks.index' , ['talks'=>Auth::user()->talks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('talks.create');
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
            'length'=>'required',
            'type'=> 'required',
            'abstract' => '',
            'organizer_notes'=> '',

        ]); 
        $validated['user_id'] = Auth::user()->id;

        Talk::insert($validated);

        return redirect()->route('talks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Talk  $talk
     * @return \Illuminate\Http\Response
     */
    public function show(Talk $talk)
    {
        return view('talks.show',['talk'=>$talk]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Talk  $talk
     * @return \Illuminate\Http\Response
     */
    public function edit(Talk $talk)
    {
        return view('talks.edit',['talk'=>$talk]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Talk  $talk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Talk $talk)
    {
      $validated = $request->validate([
            'title'=> 'required',
            'length'=>'required',
            'type'=> 'required',
            'abstract' => 'required',
            'organizer_notes'=> 'required',

        ]); 
        $talk->update($validated);
        return redirect()->route('talks.index')->with('success', 'Talk updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Talk  $talk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Talk $talk)
    {
        $talk->delete();
        return redirect()->route('talks.index')->with('success', 'Talk deleted successfully.');
    }
}
