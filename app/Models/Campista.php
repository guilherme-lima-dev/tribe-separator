<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use function Psy\debug;

class Campista extends Model
{
    protected $table = 'campistas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'genero',
        'peso',
        'altura'
    ];

    public function conhecidos()
    {
        return $this->belongsToMany(
            Campista::class,
            'campistas_conhece',
            'id_campista',
            'id_conhecido'
        );
    }

    public function confidentesConhecidos()
    {
        return $this->belongsToMany(
            Confidente::class,
            'campistas_conhece_confidentes',
            'id_campista',
            'id_confidente'
        );
    }

    public function tribo()
    {
        return $this->belongsTo(Tribo::class, 'tribo_id');
    }
    public function retornaInfracaoNessaTribo($idTribo): ?string
    {
        $msg = null;
        // Obter a tribo especificada
        $tribo = Tribo::find($idTribo);
        if (!$tribo) {
            $msg .= "Não existe essa tribo  | "; // Se a tribo não existir, retorna uma mensagem de erro
        }

        if ($tribo->campistas->count() >= 13) {
            $msg .= "A tribo está cheia | "; // Se a tribo já estiver cheia, retorna uma mensagem de erro
        }

        // Verificar se o campista conhece algum confidente da tribo
        foreach ($this->confidentesConhecidos as $confidente) {
            if ($confidente->tribo_id == $idTribo) {
                $msg .= "Conhece o Confidente {$confidente->nome} |"; // O campista conhece um confidente dessa tribo
            }
        }

        $nomesCampistas = null;
        // Verificar se o campista conhece algum outro campista já na tribo ou é conhecido por algum campista
        foreach ($tribo->campistas as $campistaNaTribo) {
            // Verifica bidirecionalmente se o campista e o outro campista se conhecem

            if (
                $this->conhecidos->contains($campistaNaTribo) ||
                $campistaNaTribo->conhecidos->contains($this)
            ) {
                $nomesCampistas .= $campistaNaTribo->nome . ", ";
            }
        }

        if (!is_null($nomesCampistas)) {
            $msg .= "Conhece o campista $nomesCampistas | "; // O campista ou o outro campista se conhecem
        }

        if (!is_null($msg)) {
            return $msg; // Se alguma das verificações falhar, retorna a mensagem de erro
        }
        // Se nenhuma das verificações falhar, o campista é válido para essa tribo
        return null;
    }

    public function campistaAtendeARegra()
    {
        // Verifica se o campista está associado a uma tribo
        if (!$this->tribo) {
            return false;
        }

        $triboId = $this->tribo_id;

        // Verifica se o campista está na mesma tribo que os confidentes que ele conhece
        foreach ($this->confidentesConhecidos as $confidente) {
            if ($confidente->tribo_id == $triboId) {
                return false; // O campista conhece um confidente da mesma tribo
            }
        }

        // Verifica se o campista está na mesma tribo que outros campistas que ele conhece ou que o conhecem
        foreach ($this->conhecidos as $conhecido) {
            if ($conhecido->tribo && $conhecido->tribo->id == $triboId) {
                return false; // O campista conhece outro campista da mesma tribo
            }
        }

        foreach ($this->tribo->campistas as $campistaNaTribo) {
            if ($campistaNaTribo->conhecidos->contains($this)) {
                return false; // Outro campista na tribo conhece este campista
            }
        }

        // Se nenhuma das condições acima for violada, o campista atende às regras
        return true;
    }


}
