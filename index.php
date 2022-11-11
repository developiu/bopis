<?php 

use XPort\Auth;
use XPort\Mvc\FrontController;
use XPort\Mvc\MvcException;

include 'vendor/autoload.php';
include 'config.php';

session_start();

$templateEngine = new League\Plates\Engine('templates');

if(!Auth::isLogged()) {
    echo $templateEngine->render('login');
    return;
}

$controller = $_GET['controller'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$frontController = new FrontController($templateEngine);
try {
    echo $frontController->process($controller,$action);
}
catch(MvcException $e) {
    echo $templateEngine->render('error404',['message' => $e->getMessage()]);
}
