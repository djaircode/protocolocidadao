<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    use HasFactory;

    protected $fillable = [
        'arquivoNome', 'codigoAnexoPublico', 'codigoAnexoSecreto', 'user_id', 'protocolo_id'
    ];

    /*

        O campo codigoAnexoPublico será um valor aleatório usado na criação do link para acesso ao arquivo (download), exemplo:
        .../sistema/anexos/2837282728/download
        o nome da pasta será dado pelo (codigoAnexoSecreto), será usado para acessar a pasta caminho onde este arquivo será armazenada no sistema e só será possível encontrar o caminho conhecendo o valor do codigoAnexo
        Essa técnica será usada para melhorar a proteção do arquivo armazenada no servidor

    */

    protected $dates = ['created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function protocolo()
    {
        return $this->belongsTo(Protocolo::class);
    }      
}
