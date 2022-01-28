<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtocoloSituacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
    ];

    public function protocolos()
    {
        return $this->hasMany('App\Protocolo');
    }    
}
