<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntervaloFieldsToPontoTable extends Migration
{
    public function up()
    {
        Schema::table('ponto', function (Blueprint $table) {
            if (!Schema::hasColumn('ponto', 'inicio_intervalo')) {
                $table->time('inicio_intervalo')->nullable()->after('entrada_status');
            }
            if (!Schema::hasColumn('ponto', 'retorno_intervalo')) {
                $table->time('retorno_intervalo')->nullable()->after('inicio_intervalo');
            }
        });
    }

    public function down()
    {
        Schema::table('ponto', function (Blueprint $table) {
            if (Schema::hasColumn('ponto', 'retorno_intervalo')) {
                $table->dropColumn('retorno_intervalo');
            }
            if (Schema::hasColumn('ponto', 'inicio_intervalo')) {
                $table->dropColumn('inicio_intervalo');
            }
        });
    }
}
