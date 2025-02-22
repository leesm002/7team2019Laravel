<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class JapanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $japans = \App\Japan::get();

        if(Auth::check()) {
            $user = Auth::user();

            $admin = $user->admin;

            return view('japan.index', compact('japans', 'admin'));
        }
        else {
            return view('japan.index', compact('japans'));            
        }
    }

    /**
     * Show the form for creating a new resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $request;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->has('img')) {
            $image = $request->file('img');
            $filename = Str::random(15).filter_var($image->getClientOriginalName(),FILTER_SANITIZE_URL);
            $image->move(public_path('img'),$filename);

            $japans = \App\Japan::create([
                'place'=>$request->place,
                'explain'=>$request->explain,
                'img'=>$filename,
            ]); 
        } 
        else {
            $japans = \App\Japan::create([
                'place'=>$request->place,
                'explain'=>$request->explain,
                'img'=>null,
            ]); 
        }

        return $japans;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $japan = \App\Japan::where('id', '=', $id)->get();

        if(Auth::check()) {
            $user = Auth::user();

            $admin = $user->admin;

            return compact('japan', 'admin');
        }
        else {
            $admin = 0;
            return compact('japan', 'admin');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if($request->has('img')) {
            $image = $request->file('img');
            $filename = Str::random(15).filter_var($image->getClientOriginalName(),FILTER_SANITIZE_URL);
            $image->move(public_path('img'),$filename);

            \App\Japan::where('id', '=', $id)->update([
                'place'=>$request->place,
                'explain'=>$request->explain,
                'img'=>$filename,
            ]);
        } else {
            \App\Japan::where('id', '=', $id)->update([
                'place'=>$request->place,
                'explain'=>$request->explain,
            ]);
        }

        $respon = \App\User::where('id', '=', $id)->get();

        return $respon;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\Japan::find($id)->delete();

        return response($id);
    }
}
