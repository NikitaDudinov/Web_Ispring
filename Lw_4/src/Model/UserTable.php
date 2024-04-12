<?php
declare(strict_types=1);
namespace App\Model;


class UserTable
{

    public function __construct(private \PDO $connection)
    {

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
                ':middle_name' => $User->GetMiddleName(),
                ':gender' => $User->GetGender(),
                ':birth_date' => $User->GetBirthDate(),
                ':email' => $User->GetEmail(),
                ':phone' => $User->GetPhone(),
                ':avatar_path' => $User->GetAvatarPath(),
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

    private function createUserFromRow(array $row): User
    {
        return new User((int)$row['user_id'], $row['first_name'], $row['last_name'],$row['middle_name'] , $row['gender'], $row['birth_date'], $row['email'], $row['phone'], $row['avatar_path']);
    }


}