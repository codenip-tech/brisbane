<?php

namespace App\Doctrine;

use App\Entity\User;
use App\Repository\UserRepository;
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

    public function find(string $id): ?User
    {
        return $this->repo->find($id);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}