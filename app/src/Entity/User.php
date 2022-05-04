<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'string', columnDefinition: 'CHAR(36) NOT NULL')]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $email;

    /** @var Organization[] */
    #[ORM\ManyToMany(targetEntity: Organization::class, inversedBy: 'users')]
    private array $organizations = [];

    public function __construct(string $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
        $this->organizations[] = Organization::create(Uuid::v4()->toRfc4122(), \sprintf('%s\'s organization', $email));
    }

    public static function create(string $id, string $email): static
    {
        return new static($id, $email);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function organizations(): array
    {
        return $this->organizations;
    }
}
