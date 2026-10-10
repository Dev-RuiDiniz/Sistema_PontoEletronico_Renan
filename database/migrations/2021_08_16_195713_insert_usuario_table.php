<?php

use Illuminate\Database\Migrations\Migration;

class InsertUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Usuários de demonstração foram removidos para não versionar dados
        // pessoais nem senhas fixas. O primeiro administrador precisa ser
        // criado por um processo seguro antes de usar a aplicação.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
