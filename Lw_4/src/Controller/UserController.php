<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\UserTable;
use App\Model\User;
use App\Infrastructure\ConnectionProvider;

class UserController 
{
    private UserTable $userTable;
    public function __construct()
    {
        $connection = ConnectionProvider::getConnection();
        $this->userTable = new UserTable($connection);
    }

    public function index(): void
    {
        require __DIR__ . '/../View/choise_action.php';
    }

    public function publishUser(array $request): ?int
    {
        try{
            $user = new User(
                null, 
                $request['first_name'], 
                $request['last_name'], 
                $request['middle_name'] ?? NULL, 
                $request['gender'], 
                $request['birth_date'], 
                $request['email'], 
                $request['phone'] ?? NULL,
                $request['avatar_path'] ?? NULL,
            );
            $userId = $this->userTable->add($user);
            if(!empty($_FILES["ImageFile"]["tmp_name"])){
                $format = mime_content_type($_FILES["ImageFile"]["tmp_name"]) ;
                switch ($format) {
                    case 'image/png':
                        $format = '.png';
                        break;
                    case 'image/gif':
                         $format = '.gif';
                        break;
                    case 'image/jpeg':
                         $format = '.jpeg';
                        break;
                    default:
                        $format = null;
                }
                if($format != null){
                    move_uploaded_file($_FILES['ImageFile']['tmp_name'], "uploads/avatar" . $userId . $format);
                    $this->userTable->update_avatar_path($userId, $format);
                }
                else{
                    echo "Неверный формат картинки";
                }
            }
            return $userId;
        }catch(\Exception $e){
            echo "Error database:". $e->getMessage() ."";
            return null;
        }

    }

    public function showUser(array $request): ?User
    {
        $id = $request['user_id'] ?? null;
        if ($id === null)
        {
            throw new \InvalidArgumentException('Parameter id is not defined');
        }
        $user = $this->userTable->find((int) $id);
        if($user != null){
            require __DIR__ . '/../View/user.php';
        }
        return $user;
    }
    public function showAllUser(): array
    {
        $users = $this->userTable->findAllUsers();
        return $users;
    }

    public function deleteUser(array $request): ?int
    {
       $id = $request['user_id'] ?? null;
        if ($id === null)
        {
            throw new \InvalidArgumentException('Parameter id is not defined');
        }
        return $this->userTable->delete((int) $id);
    }

    public function updateUser(array $request): ?User
    {
        $userId = $request['user_id'];
        $user = $this->userTable->find((int) $userId);
        if($user != NULL){
            try{
                $this->userTable->update($user, $request);
                if(!empty($_FILES["ImageFile"]["tmp_name"])){
                    $format = mime_content_type($_FILES["ImageFile"]["tmp_name"]) ;
                    switch ($format) {
                        case 'image/png':
                            $format = '.png';
                            break;
                        case 'image/gif':
                             $format = '.gif';
                            break;
                        case 'image/jpeg':
                             $format = '.jpeg';
                            break;
                        default:
                            $format = null;
                    }
                    if($format != null){
                        move_uploaded_file($_FILES['ImageFile']['tmp_name'], "uploads/avatar" . $userId . $format);
                        $this->userTable->update_avatar_path(intval($userId), $format);
                    }
                } 
            $user = $this->userTable->find((int) $userId);    
            return $user;
            }catch(\Exception $e){
                return null;
            }
        }else{
            header("Location: http://localhost:8000/");
            die();
        }
    }
}