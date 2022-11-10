<?php 

include 'vendor/autoload.php';

$templateEngine = new League\Plates\Engine('templates');


try {
    echo $templateEngine->render('dashboard');
}
catch(\Exception $e) {
    echo $templateEngine->render('error404',['message' => 'Pagina inesistente']);
}
