<?php


use App\Models\Images;
use Illuminate\Database\Eloquent\Collection;


class ImagesRepo{

    protected $images;


    public function __construct(Images $images)
    {
        $this->images = $images;
    }

}


