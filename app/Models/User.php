<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'setor_id',
        'matricula'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

 /**
     * Verifica se o operador está ativo.
     *
     * @var none
     */
    public function hasAccess(){
        return ($this->active == 'Y') ? true : false;
    }

    /**
     * Perifs do operador
     *
     * @var Role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Verifica se um operador tem determinado(s) perfil(is)
     *
     * @var Bool
     */
    public function hasRoles($roles)
    {
        $userRoles = $this->roles;
        return $roles->intersect($userRoles)->count();
    }
    
    /**
     * Verifica se um operador tem determinado perfil
     *
     * @var Bool
     */
    public function hasRole($role)
    {
        if(is_string($role)){
          $role = Role::where('name','=',$role)->firstOrFail();
        }
        return (boolean) $this->roles()->find($role->id);

    }

    /**
     * Cada funcionário deverá ser relacionado a um setor
     *
     * @var Bool
     */
    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }
    
    /**
     * Cada protocolo deverá ser relacionado a um funcionário, que o abriu
     *
     * @var Bool
     */
    public function protocolos_user()
    {
        return $this->hasMany(Protocolo::class, 'user_id');
    }

    /**
     * Cada protocolo deverá ser relacionado a um funcionário, que o abriu
     *
     * @var Bool
     */
    public function anexos_user()
    {
        return $this->hasMany(Anexo::class, 'user_id');
    }

    // Tabela de acesso aos anexos por usuario
    // relação many-to-many
    public function anexos()
    {
        return $this->belongsToMany(Anexo::class);
    }

    // Tabela de acesso aos protocolos por usuario
    // relação many-to-many
    public function protocolos()
    {
        return $this->belongsToMany(Protocolo::class);
    }  
}
