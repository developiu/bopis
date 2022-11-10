<?php 

use XPort\Auth;

include 'vendor/autoload.php';
include 'config.php';

session_start();

$templateEngine = new League\Plates\Engine('templates');

if(!Auth::isLogged()) {
    echo $templateEngine->render('login');
    return;
}


$page = $_GET['page'] ?? 'dashboard';

try {
    echo $templateEngine->render($page);
}
catch(\Exception $e) {
    echo $templateEngine->render('error404',['message' => 'Pagina inesistente']);
}
