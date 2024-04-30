<?php
declare(strict_types=1);

namespace App\Database;

use App\Model\User;

    class UserTable
    {
        private \PDO $connection;
    
        public function __construct(\PDO $connection)
        {
            $this->connection = $connection;
        }
    
        public function add(User $User): int
        {
            $query = 'INSERT INTO `user` (`first_name`, `last_name`, `middle_name`, `gender`, `birth_date`, `email`, `phone`, `avatar_path`)
            VALUES (:first_name, :last_name, :middle_name, :gender, :birth_date, :email, :phone, :avatar_path);';
            $statement = $this->connection->prepare($query);
            try {
                $statement->execute([
                    ':first_name' => $User->GetFirstName(),
                    ':last_name' => $User->GetLastName(),
                    ':middle_name' => !empty($User->GetMiddleName()) ? $User->GetMiddleName() : null,
                    ':gender' => $User->GetGender(),
                    ':birth_date' => $User->GetBirthDate(),
                    ':email' => $User->GetEmail(),
                    ':phone' => !empty($User->GetPhone()) ? $User->GetPhone() : null,
                    ':avatar_path' => !empty($User->GetAvatarPath()) ? $User->GetAvatarPath(): null,
                ]);
                    return (int)$this->connection->lastInsertId();
            }catch(\PDOException $e){
                throw new \RuntimeException($e->getMessage(), (int) $e->getCode());
            }
    
        }
    
        public function find(int $userId): ?User
        {
            $query = "SELECT user_id, first_name, last_name, middle_name, gender, birth_date, email, phone, avatar_path   FROM user WHERE user_id = :user_id";
            $statement = $this->connection->prepare($query);
            $statement->execute(
                [
                    ':user_id' => $userId
                ]
            );
            try{
                if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {       
                    return $this->createUserFromRow($row);
                }
                return null;
            } catch(\PDOException $e){
                throw new \RuntimeException($e->getMessage(), (int) $e->getCode());
            }
        }
    
        public function delete(int $userId): ?int
        {
            $query = "DELETE FROM user  WHERE user_id = :user_id";
                $statement = $this->connection->prepare($query);
                try {
                    $statement->execute([
                        ':user_id' => $userId
                    ]);
                        return $userId;
                }catch(\PDOException $e){
                    throw new \RuntimeException($e->getMessage(), (int) $e->getCode());
                }
         
        }
    
        public function update($key, $value, int $userId): void
        {
            $query = "UPDATE user SET $key = '$value'  WHERE user_id = :user_id";
            $statement = $this->connection->prepare($query);
            try {
                $statement->execute([
                    ":user_id" => $userId
                ]);
            }catch(\PDOException $e){
                throw new \RuntimeException($e->getMessage(), (int) $e->getCode());
            }
        }
    
        public function update_avatar_path(int $userId, string $format): void
        {
            $query = "UPDATE user SET avatar_path = :link_avatar WHERE user_id = $userId";
            $statement = $this->connection->prepare($query);
            $statement->execute(
                [
                    ':link_avatar' => "avatar".$userId . $format
                ]
            );
        }
        private function createUserFromRow(array $row): User
        {
            return new User((int)$row['user_id'], $row['first_name'], $row['last_name'],$row['middle_name'] , $row['gender'], $row['birth_date'], $row['email'], $row['phone'], $row['avatar_path']);
        }
    
    
    }
