<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tabel_ticket', function (Blueprint $table) {
            $table->id('id_ticket');
            $table->unsignedBigInteger('id_user');
            $table->string('judul_masalah', 200);
            $table->text('deskripsi');
            $table->string('kategori', 100);
            $table->enum('prioritas', ['Low', 'Medium', 'High']);
            $table->enum('status', ['Open', 'In Progress', 'Resolved', 'Closed']);
            $table->datetime('tanggal_lapor');
            $table->timestamps();
            
            // Foreign key ke tabel_user
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('tabel_user')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tabel_ticket');
    }
};