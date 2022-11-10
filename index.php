<?php 

include 'vendor/autoload.php';

$templateEngine = new League\Plates\Engine('templates');

$page = $_GET['page'] ?? 'dashboard';

try {
    echo $templateEngine->render($page);
}
catch(\Exception $e) {
    echo $templateEngine->render('error404',['message' => 'Pagina inesistente']);
}
