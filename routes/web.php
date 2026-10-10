<?php

Route::group(['namespace' => 'PontoEletronico'], function () {
    Route::get('/', 'IndexController@index')->name('inicio');

    Route::get('/login', 'LoginController@show')->name('login');
    Route::post('/login', 'LoginController@login')->name('login.autenticar');
    Route::get('/sair', 'LoginController@sair')->name('sair');

    Route::get('/dashboard', 'DashboardController@index')
        ->middleware('authMiddleware')
        ->name('dashboard');
    Route::post('/registrar', 'PontoController@registrar_validando')
        ->middleware('authMiddleware')
        ->name('ponto.registrar');

    Route::get('/cadastro', 'UsuarioController@novo')
        ->middleware('adminMiddleware')
        ->name('cadastro');
    Route::post('/cadastro', 'UsuarioController@salvarNovo')
        ->middleware('adminMiddleware')
        ->name('cadastro.salvar');

    Route::get('/admin', 'AcompanhamentoController@admin')
        ->middleware('adminMiddleware')
        ->name('admin');
    Route::get('/admin/espelho', 'AcompanhamentoController@espelho')
        ->middleware('adminMiddleware')
        ->name('admin.espelho');
    Route::get('/admin/espelho/exportar', 'AcompanhamentoController@exportarEspelho')
        ->middleware('adminMiddleware')
        ->name('admin.espelho.exportar');
    Route::get('/admin/funcionarios/{id}/pontos', 'AcompanhamentoController@pontosFuncionario')
        ->where('id', '[0-9]+')
        ->middleware('adminMiddleware')
        ->name('admin.funcionario.pontos');
    Route::post('/admin/restricao-ip', 'AcompanhamentoController@salvarRestricaoIp')
        ->middleware('adminMiddleware')
        ->name('admin.restricao-ip');

    // Redireciona os endereços antigos do painel para as telas atuais.
    Route::get('/painel', function () {
        return redirect('/login');
    });
    Route::get('/painel/{caminho?}', function () {
        return redirect('/admin');
    })->where('caminho', '.*');
});
