<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tabel_ticket';
    protected $primaryKey = 'id_ticket';
    public $timestamps = true;

    protected $fillable = [
        'id_user', 'judul_masalah', 'deskripsi', 'kategori', 
        'prioritas', 'status', 'tanggal_lapor'
    ];

    protected $casts = [
        'tanggal_lapor' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kategoriMasalah()
    {
        return $this->belongsTo(KategoriMasalah::class, 'kategori', 'nama_kategori');
    }

    public function responses()
    {
        return $this->hasMany(TicketRespon::class, 'id_ticket')->orderBy('tanggal_respon', 'desc');
    }

    public function statusLogs()
    {
        return $this->hasMany(StatusLog::class, 'id_ticket')->orderBy('tanggal_update', 'desc');
    }

    public function updateStatus($newStatus, $userId, $notes = null)
    {
        $oldStatus = $this->status;
        
        if ($oldStatus !== $newStatus) {
            $this->status = $newStatus;
            $this->save();

            StatusLog::create([
                'id_ticket' => $this->id_ticket,
                'status_lama' => $oldStatus,
                'status_baru' => $newStatus,
                'tanggal_update' => now()
            ]);
        }
    }

    public function getTicketNumberAttribute()
    {
        return 'TKT-' . str_pad($this->id_ticket, 5, '0', STR_PAD_LEFT);
    }
}