<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Confidente extends Model
{
    protected $table = 'confidentes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'tribo_id'
    ];

    public function campistas()
    {
        return $this->belongsToMany(
            Campista::class,
            'campistas_conhece_confidentes',
            'id_confidente',
            'id_campista'
        );
    }

    public function tribo()
    {
        return $this->belongsTo(Tribo::class, 'tribo_id');
    }
}
