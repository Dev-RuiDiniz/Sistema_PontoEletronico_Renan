<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracaoSistemaTable extends Migration
{
    public function up()
    {
        Schema::create('configuracao_sistema', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('restricao_ip_ativa')->default(false);
            $table->string('ip_autorizado', 45)->nullable();
            $table->timestamps();
        });

        DB::table('configuracao_sistema')->insert([
            'id' => 1,
            'restricao_ip_ativa' => false,
            'ip_autorizado' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('configuracao_sistema');
    }
}
