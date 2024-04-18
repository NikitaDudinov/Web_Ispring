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
        require __DIR__ . '/../View/add_post_form.php';
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

    public function showUser(array $request): void
    {
        $id = $request['user_id'] ?? null;
        if ($id === null)
        {
            throw new \InvalidArgumentException('Parameter id is not defined');
        }
        $user = $this->userTable->find((int) $id);
        require __DIR__ . '/../View/user.php';
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

    public function updateUser(array $request): void
    {
        $userId = $request['user_id'];
        foreach($request as $key => $value){
            $this->userTable->update($key, $value, $userId);
        }
    }
}