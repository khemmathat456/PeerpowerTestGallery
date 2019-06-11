<?php

namespace App\Http\Controllers;

use App\Images;
use App\User;
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
//        dd(Images::first()->user()->get('name'));
        return Images::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('images_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $path = Storage::putFile('images', $request->file('fileUpload'));
        $request = $request->file('fileUpload');
//        dd($request->validate([
//
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10485760',
//        ]));
        Images::create([
            'name' => $request->getClientOriginalName(),
            'size' => $request->getSize(),
            'type' => $request->getMimeType(),
            'user_id' => auth()->id(),
            'path' => $path,
        ]);
        return redirect('/images');
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
