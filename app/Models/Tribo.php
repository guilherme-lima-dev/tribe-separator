<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tribo extends Model
{
    protected $table = 'tribo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nome_tribo'
    ];

    public function campistas()
    {
        return $this->hasMany(Campista::class, 'tribo_id');
    }

    public function confidentes()
    {
        return $this->hasMany(Confidente::class, 'tribo_id');
    }

    public function estaValida()
    {
        $campistas = $this->campistas;

        // Conta o total de campistas
        $totalCampistas = $campistas->count();

        // Verifica se a tribo está válida
        $minCampistas = 11;
        $maxCampistas = 13;

        // Regras de validação
        if ($totalCampistas >= $minCampistas && $totalCampistas <= $maxCampistas) {
            return true; // A tribo está válida
        }

        return false; // A tribo não está válida
    }


}
