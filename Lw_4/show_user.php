<?php
require_once __DIR__ .'/vendor/autoload.php';
use App\Controller\UserController;
if(!empty($_GET['user_id'])){
    $controller = new UserController();
    $user = $controller->showUser($_GET);
    if(!empty($user)){
        ?>
        <form action="/update_user.php" method="post" enctype="multipart/form-data">
            <div>
                <input name="user_id" id="user_id" type="hidden"value=<?=$_GET['user_id']?>>
             </div>
            <button type="submit">Изменить пользоваетеля</button>
        </form>
        <form action="/delete_user.php" method="post" enctype="multipart/form-data">
            <div>
                <input name="user_id" id="user_id" type="hidden"value=<?=$_GET['user_id']?>>
            </div>
            <button type="submit">Удалить пользоваетеля</button>
        </form>
        <?php
    }
    else{
        echo "Пользователя с таким id не существует";
    }
} else {
    echo('Неверный GET-запрос');
}?>

