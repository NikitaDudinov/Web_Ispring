<?php
declare(strict_types=1);

namespace App\Repository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
class UserRepository
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function findById(int $id): ?User
    {
        $this->entityManager->flush();
        return $this->repository->findOneBy(['userId' => (string) $id]);
    }

    public function store(User $user): int
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user->getUserId();
    }
    public function update(User $new_user): void
    {
        $user = $this->repository->findOneBy(['userId' => (string) $new_user->getUserId()]);
        $user = $new_user;
        $this->entityManager->flush();
    }

    public function update_avatar_path($userId, $format):void
    {
        $user = $this->repository->findOneBy(['userId' => (string) $userId]);
        $path = "avatar".$userId . $format;
        $user->setAvatarPath($path);
        $this->entityManager->flush();
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * @return User[]
     */
    public function listAll(): array
    {
        return $this->repository->findAll();
    }
}