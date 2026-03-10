<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketRespon extends Model
{
    use HasFactory;

    protected $table = 'tabel_ticket_respon';
    protected $primaryKey = 'id_respon';
    public $timestamps = true;

    protected $fillable = ['id_ticket', 'id_admin', 'pesan_respon', 'tanggal_respon'];

    protected $casts = [
        'tanggal_respon' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'id_ticket');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }
}