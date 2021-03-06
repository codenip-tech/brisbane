<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Exception\EntityNotFoundException;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineUserRepository implements UserRepository
{
    private EntityRepository $repo;
    private EntityManager $entityManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->repo = $managerRegistry->getRepository(User::class);
        $this->entityManager = $managerRegistry->getManager();
    }

    public function findOneByIdOrFail(string $id): User
    {
        if (null === $user = $this->repo->find($id)) {
            throw EntityNotFoundException::fromClassAndId(User::class, $id);
        }

        return $user;
    }

    public function findOneById(string $id): ?User
    {
        return $this->repo->find($id);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
