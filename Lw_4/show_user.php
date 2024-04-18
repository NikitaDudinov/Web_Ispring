<?php
require_once __DIR__ .'/vendor/autoload.php';
use App\Controller\UserController;
if(!empty($_GET['user_id'])){
    $controller = new UserController();
    $controller->showUser($_GET);
} else {
    echo('Неверный GET-запрос');
}

