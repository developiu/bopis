<?php 

use XPort\Auth;
use XPort\Mvc\FrontController;
use XPort\Mvc\MvcException;

include 'vendor/autoload.php';
include 'config.php';

session_start();

$templateEngine = new League\Plates\Engine('views');
$frontController = new FrontController($templateEngine);

if(!Auth::isLogged()) {
    $frontController->process('auth','login');
    return;
}

$controller = $_GET['controller'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
try {
    echo $frontController->process($controller,$action);
}
catch(MvcException $e) {
    echo $templateEngine->render('error-pages/general_error',['error_code' => 404, 'message' => $e->getMessage()]);
}
