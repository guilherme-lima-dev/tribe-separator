<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampistaConhece extends Model
{
    protected $table = 'campistas_conhece';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'id_campista',
        'id_conhecido'
    ];
}
