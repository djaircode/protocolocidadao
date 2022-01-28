<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo', 'descricao', 'contato'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function protocolos()
    {
        return $this->hasMany('App\Protocolo');
    } 
}
