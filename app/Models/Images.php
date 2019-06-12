<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Images extends Model
{
    use SoftDeletes;

    protected $table = 'images';

    protected $fillable = [
        'name',
        'name_unique',
        'size',
        'type',
        'user_id',
        'path',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
