<?php

namespace App\Http\Controllers;

use App\Http\Requests\imagesRequest;
use App\Models\Images;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


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
        $images = Images::all()->where('user_id',auth()->id());
        return $images;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('upload');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(imagesRequest $request)
    {

        $image = $request->file('fileUpload');
        $path = Storage::disk('public')->putFile('images', $image);
            Images::create([
                'name' => $image->getClientOriginalName(),
                'name_unique' => basename($path),
                'size' => $image->getSize(),
                'type' => $image->getMimeType(),
                'user_id' => auth()->id(),
                'path' => $path,
            ]);
//         Repo
        $user = User::all()->where('id',auth()->id())->first();
        $user->update(['data_used'=>$user->data_used+$image->getSize()]);

        return basename($path);

//        return redirect('/images');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Images  $photoModel
     * @return \Illuminate\Http\Response
     */
    public function show(Images $photoModel, $id)
    {
        $path = storage_path('app/public/images/' . $id);
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
    public function edit(Images $photoModel)
    {
        //
    }

     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Images  $photoModel
     * @return \Illuminate\Http\Response
    public function update(Request $request, Images $photoModel)
    {
        //
    }

     * Remove the specified resource from storage.
     *
     * @param  \App\Images  $photoModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Images $photoModel,$id)
    {
        Storage::disk('public')->delete('images/'.$id);
        $photoModel->where('name_unique', $id)->delete();
//        return redirect('images');
    }
}
