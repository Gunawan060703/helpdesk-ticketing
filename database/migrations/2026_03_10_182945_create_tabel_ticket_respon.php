<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tabel_ticket_respon', function (Blueprint $table) {
            $table->id('id_respon');
            $table->unsignedBigInteger('id_ticket');
            $table->unsignedBigInteger('id_admin');
            $table->text('pesan_respon');
            $table->datetime('tanggal_respon');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_ticket')
                  ->references('id_ticket')
                  ->on('tabel_ticket')
                  ->onDelete('cascade');
                  
            $table->foreign('id_admin')
                  ->references('id_user')
                  ->on('tabel_user')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tabel_ticket_respon');
    }
};