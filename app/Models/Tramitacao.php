<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramitacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'protocolo_id', 'user_id_origem', 'setor_id_origem', 'user_id_destino', 'setor_id_destino', 'mensagem', 'recebido_em', 'recebido', 'mensagemRecebido', 'tramitado_em', 'tramitado'
    ];

    protected $dates = ['created_at', 'recebido_em', 'tramitado_em'];

    public function protocolo()
    {
        return $this->belongsTo('App\Protocolo');
    }

    public function userOrigem()
    {
        return $this->belongsTo('App\User', 'user_id_origem');
    }

    public function setorOrigem()
    {
        return $this->belongsTo('App\Setor', 'setor_id_origem');
    }

    public function userDestino()
    {
        return $this->belongsTo('App\User', 'user_id_destino');
    }    
    
    public function setorDestino()
    {
        return $this->belongsTo('App\Setor', 'setor_id_destino');
    }    
}
