<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Controller\UserController;
try{
    $controller = new UserController();
}catch(\PDOException $e) {
    echo "Error connection database:". $e->getMessage() ."";
}
try{
    $controller->index();
}catch(\Exception $e){
    echo "Error database:". $e->getMessage() ."";
}

