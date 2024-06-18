<?php
// use App\Service\UserServiceInterface;
namespace App\Service;
use App\Repository\UserRepository;
use App\Service\Data\UserData;
use App\Entity\User;

class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function saveUser(string $firstName, string $lastName, ?string $middleName, string $gender, string $birthDate, string $email, ?string $phone, ?string $pathImage):?int
    {
        if(!empty($firstName) && !empty($lastName) && !empty($gender) && !empty($birthDate) && !empty($email)){
            $user = new User(
                null, 
                $firstName,
                $lastName,
                $middleName,
                $gender,
                $birthDate,
                $email,
                $phone,
                $pathImage
            );
            $userId = $this->userRepository->store($user);
            return $userId;
        }
        return null;
    }

    public function getUser(int $userId): ?UserData
    {
        $user = $this->userRepository->findById($userId);
        if ($user !== null)
        {
            return new UserData(
                $user->getUserId(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getMiddleName(),
                $user->getGender(),
                $user->getBirthDate(),
                $user->getEmail(),
                $user->getPhone(),
                $user->getAvatarPath()
            );           
        }
        else
        {
            return null;
        }
    }

    public function getUserPathImage(int $userId): ?string
    {
        $user = $this->userRepository->findById($userId);
        return $user->getAvatarPath();
    }
    public function deleteUser(int $userId): ?string
    {
        $user = $this->userRepository->findById($userId);
        if(!empty($user))
        {
            $this->userRepository->delete($user);
            return $user->getAvatarPath();
        }
        else
        {
            return false;
        }
    }

    public function showUsers(): array
    {
        $users = $this->userRepository->listAll();
        return $users;
    }

    public function updateUser(int $userId, ?string $firstName, ?string $lastName, ?string $middleName, ?string $gender, ?string $birthDate, ?string $email, ?string $phone, ?string $pathImage): UserData
    {
        $user = $this->userRepository->findById($userId);
        if(!empty($firstName))
        {
            $user->setfirstName($firstName);
        }
        if(!empty($lastName))
        {
            $user->setlastName($lastName);
        }
        if(!empty($middleName))
        {
            $user->setmiddleName($middleName);
        }
        if(!empty($gender))
        {
            $user->setGender($gender);
        }
        if(!empty($email))
        {
            $user->setEmail($email);
        }               
        if(!empty($phone))
        {
            $user->setPhone($phone);
        }               
        if(!empty($birthDate))
        {
            $user->setBirthDate($birthDate);
        }     
        if(!empty($pathImage))
        {
            $user->setAvatarPath($pathImage);
        }  
        $this->userRepository->update($user);
        return new UserData(
            $user->getUserId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getMiddleName(),
            $user->getGender(),
            $user->getBirthDate(),
            $user->getEmail(),
            $user->getPhone(),
            $user->getAvatarPath()
        );             
    }
}
