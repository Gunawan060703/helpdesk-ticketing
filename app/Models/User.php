<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tabel_user';
    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'nama', 'email', 'password', 'role', 'departemen', 'create_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'create_at' => 'datetime',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'id_user');
    }

    public function responses()
    {
        return $this->hasMany(TicketRespon::class, 'id_admin');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}