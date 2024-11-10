<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampistaConheceConfidente extends Model
{
    protected $table = 'campistas_conhece_confidentes';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'id_campista',
        'id_confidente'
    ];
}
