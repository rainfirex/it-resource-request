<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signatures extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'picture'
    ];
}
