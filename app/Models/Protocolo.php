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
        return $this->belongsTo(Setor::class);
    }

    public function protocoloTipo()
    {
        return $this->belongsTo(ProtocoloTipo::class);
    }

    public function protocoloSituacao()
    {
        return $this->belongsTo(ProtocoloSituacao::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function protocoloTramitacaos()
    {
        return $this->hasMany(ProtocoloTramitacao::class);
    }

    /**
     * Pega todos anexos de um protocolo
    */
    public function anexos()
    {
        return $this->hasMany(Anexo::class);
    }


    public function users()
    {
        return $this->belongsToMany(User::class);
    }        
}
