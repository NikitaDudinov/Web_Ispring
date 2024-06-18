<?php
namespace App\Service;
use App\Service\Data\UserData;
interface UserServiceInterface
{
      public function saveUser(string $first_name, string $last_name, ?string $middle_name, string $gender, string $birth_date, string $email, ?string $phone, ?string $path_image): ?int;

      public function getUser(int $userId): ?UserData;

      public function deleteUser(int $userId): ?string;

      public function showUsers(): array;

      public function updateUser(int $UserId, ?string $first_name, ?string $last_name, ?string $middle_name, ?string $gender, ?string $birth_date, ?string $email, ?string $phone, ?string $path_image): UserData;

      public function getUserPathImage(int $userId): ?string;
}
