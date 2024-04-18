<?php
use App\Controller\UserController;

require_once __DIR__ .'/vendor/autoload.php';
$controller = new UserController();
$userId = $controller->publishUser($_POST);
if ($userId != null){
    $redirectUrl = "/show_user.php?user_id=$userId";
    header('Location: ' . $redirectUrl, true, 303);
    die();
}



