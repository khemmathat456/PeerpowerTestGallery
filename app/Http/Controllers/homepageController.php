<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Images;
use App\Models\User;

class homepageController extends Controller
{
    public function overview()
    {
        $images = Images::all()->where('user_id',auth()->id());

        $counter_by_type = $images->countBy('type')->toArray();
        $total_images = count($images);
        $total_size = $images->sum('size');
        $size_by_type = $images->groupBy('type')->map(function ($image) {
            return $image->sum('size');
        })->toArray();

        return view('images_index', ['size_by_type'=>$size_by_type,
            'total_images'=>$total_images, 'counter'=>$counter_by_type, 'data_used'=>$total_size]);
    }
}
