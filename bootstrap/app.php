<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([ ])
    ->withMiddleware(function (Middleware $middleware): void {
	$middleware->alias([
    	    'tenant' => \App\Http\Middleware\SetCurrentTenant::class,
    	]);
    })
    ->withCommands([
    	App\Console\Commands\TenantBuildCommand::class,
    	App\Console\Commands\TenantsRebuildCommand::class,
    	App\Console\Commands\TenantList::class,
	    App\Console\Commands\TenantSyncConfigCommand::class,
	    App\Console\Commands\AsteriskReload::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
