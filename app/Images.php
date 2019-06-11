<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'name',
        'size',
        'type',
        'user_id',
        'path',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
