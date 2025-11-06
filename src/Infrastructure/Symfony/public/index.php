<?php

// CHARGEMENT FORCÉ DE L'AUTOLOAD PSR-4 AVANT symfony/runtime
$autoloadFile = dirname(__DIR__, 2) . '/vendor/autoload.php';
if (!file_exists($autoloadFile)) {
    http_response_code(500);
    die('vendor/autoload.php introuvable – lancez make prepare');
}
require $autoloadFile;

// Maintenant symfony/runtime peut démarrer
require dirname(__DIR__) . '/vendor/autoload_runtime.php';

use App\Infrastructure\Symfony\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

$_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? ($_SERVER['APP_ENV'] !== 'prod');

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
