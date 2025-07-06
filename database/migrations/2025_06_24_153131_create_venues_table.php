<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('venues', function (Blueprint $table) {
        $table->id();
        $table->string('nama_lapangan');
        $table->enum('jenis', ['futsal', 'badminton','mini soccer']);
        $table->integer('harga_per_jam');
        $table->string('gambar')->nullable();
        $table->text('deskripsi')->nullable();
        $table->boolean('status')->default(true); // aktif atau tidak
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
