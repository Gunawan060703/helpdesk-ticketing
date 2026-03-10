<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLog extends Model
{
    use HasFactory;

    protected $table = 'tabel_status_log';
    protected $primaryKey = 'id_log';
    public $timestamps = true;

    protected $fillable = ['id_ticket', 'status_lama', 'status_baru', 'tanggal_update'];

    protected $casts = [
        'tanggal_update' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'id_ticket');
    }
}