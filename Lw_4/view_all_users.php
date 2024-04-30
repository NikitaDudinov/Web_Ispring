<style>
    div {
        display: flex;
    }
    .button {
        margin-left: 15px;
    }
</style>
<?php
use App\Controller\UserController;
require_once __DIR__ .'/vendor/autoload.php';
$controller = new UserController();
$users = $controller->showAllUser();
foreach($users as $key => $value){
    ?>
        <div>
            <a href="/show_user.php?user_id=<?=$key?>"><?=$value?></a>
            <form action="/delete_user.php" method="post">
            <input name="user_id" id="user_id" type="hidden" value=<?=$key?>>
            <button class="button" type="submit">Delete</button>
            </form>
        </div>
    <?php
}