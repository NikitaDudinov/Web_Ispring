<?php
use App\Controller\UserController;

require_once __DIR__ .'/vendor/autoload.php';

$controller = new UserController();
$userId = $controller->deleteUser($_POST);
if ($userId != null){
    echo 'Пользователь с id = '. $userId . ' успешно удален';
}
else{
    echo 'Такого пользователя не найдено';
}