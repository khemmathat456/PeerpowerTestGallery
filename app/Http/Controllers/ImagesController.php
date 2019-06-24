<?php

namespace App\Http\Controllers;

use App\Http\Requests\imagesRequest;
use App\Models\Images;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Images::where('user_id',auth()->id())->orderBy('created_at', 'asc')->get();
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
        $response = Images::create([
            'name' => $image->getClientOriginalName(),
            'name_unique' => basename($path),
            'size' => $image->getSize(),
            'type' => $image->getMimeType(),
            'user_id' => auth()->id(),
            'path' => $path,
        ]);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Images  $photoModel
     * @return \Illuminate\Http\Response
     */
    public function show(Images $photoModel, $id)
    {
//         Check image belong to user.
//        if ($photoModel->user_id !== auth()->user()->id) {
//            throw new BadRequestHttpException();
//        }

        $path = $photoModel::where('user_id',auth()->id())->where('name_unique', $id)->first()->path;
        $file = Storage::disk('public')->get($path);
        return $file;
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
    public function destroy(Images $photoModel, $id)
    {
        $path = $photoModel::where('user_id',auth()->id())->where('name_unique', $id)->first()->path;
        Storage::disk('public')->delete($path);
        $photoModel->where('name_unique', $id)->delete();
    }
}
