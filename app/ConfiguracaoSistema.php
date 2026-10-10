<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoSistema extends Model
{
    protected $table = 'configuracao_sistema';
    protected $fillable = ['restricao_ip_ativa', 'ip_autorizado'];
}
