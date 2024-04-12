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
}