<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    use HasFactory;

    protected $fillable = [
        'arquivoNome', 'codigoPasta', 'codigoAnexo', 'user_id',
    ];

    /*

        O campo codigoAnexo será um valor aleatório usado na criação do link para acesso ao arquivo (download), exemplo:
        .../sistema/anexos/2837282728/download
        o código da pasta (codigoPasta) será usado para acessar a pasta caminho onde este arquivo será armazenada no sistema e só será possível encontrar o caminho conhecendo o valor do codigoAnexo
        Essa técnica será usada para melhorar a proteção do arquivo armazenada no servidor

    */

    protected $dates = ['created_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }        
}
