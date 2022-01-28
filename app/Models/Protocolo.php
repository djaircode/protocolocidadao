<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Protocolo extends Model
{
    use HasFactory;

    protected $fillable = [
        'conteudo', 'referencia', 'setor_id', 'protocolo_tipo_id', 'protocolo_situacao_id', 'user_id', 'concluido_em', 'concluido', 'concluido_mensagem'
    ];

    protected $dates = ['created_at', 'concluido_em'];

        public function setor()
    {
        return $this->belongsTo('App\Setor');
    }

    public function protocoloTipo()
    {
        return $this->belongsTo('App\ProtocoloTipo');
    }

    public function protocoloSituacao()
    {
        return $this->belongsTo('App\ProtocoloSituacao');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function protocoloTramitacaos()
    {
        return $this->hasMany('App\ProtocoloTramitacao');
    }

    /**
     * Pega todos anexos de um protocolo
    */
    public function anexos()
    {
        return $this->morphMany('App\Anexo', 'anexoable');
    }

    public function notas()
    {
        return $this->morphMany('App\Nota', 'notaable');
    }    
}
