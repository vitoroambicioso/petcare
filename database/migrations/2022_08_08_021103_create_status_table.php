<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idAdmin')->nullable();
            $table->unsignedBigInteger('idDenuncia')->nullable();
            $table->string('admin')->nullable();
            $table->string('org')->nullable();
            $table->string('status');
            $table->string('message')->nullable();
            $table->timestamps();
        });

        Schema::table('statuses', function (Blueprint $table) {
            $table->foreign('idAdmin')->references('id')->on('admins');
            $table->foreign('idDenuncia')->references('id')->on('denuncias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
};
