<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tabel_status_log', function (Blueprint $table) {
            $table->id('id_log');
            $table->unsignedBigInteger('id_ticket');
            $table->string('status_lama', 50);
            $table->string('status_baru', 50);
            $table->datetime('tanggal_update');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('id_ticket')
                  ->references('id_ticket')
                  ->on('tabel_ticket')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tabel_status_log');
    }
};