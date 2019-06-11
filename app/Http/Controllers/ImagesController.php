<?php

namespace App\Http\Controllers;

use App\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return 'Get Method';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request);
//        Get FileName
//        dd($request->file('image')->getClientOriginalName());
        dd($request->validate([

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10485760',

        ]));
        $path = Storage::putFile('images', $request->file('image'));
        return $path;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Images  $photoModel
     * @return \Illuminate\Http\Response
     */
    public function show(Images $photoModel, $id)
    {
        $path = storage_path('app/images/' . $id);
        $file = File::get($path);
        $type = File::mimeType($path);

        return response($file)
            ->header('Content-Type', $type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Images  $photoModel
     * @return \Illuminate\Http\Response
     */
    public function edit(Images $photoModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Images  $photoModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Images $photoModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Images  $photoModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Images $photoModel)
    {
        //
    }
}
