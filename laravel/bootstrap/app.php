<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php', // <--- This line is required!
		channels: __DIR__ . '/../routes/channels.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware): void {
		// $middleware->alias([
		// 	'admin' => RoleMiddleware::class,
		// ]);
	
		$middleware->alias([
			'role' => RoleMiddleware::class,
		]);
		$middleware->api(prepend: [
			EnsureFrontendRequestsAreStateful::class
		]);
		$middleware->append(HandleCors::class);


	})
	->withExceptions(function (Exceptions $exceptions): void {
		//
	})->create();
