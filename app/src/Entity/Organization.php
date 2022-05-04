<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Organization
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'string', columnDefinition: 'CHAR(36) NOT NULL')]
    private readonly string $id;

    #[ORM\Column(length: 100)]
    private string $name;

    /** @var User[] */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'organizations')]
    private array $users = [];

    private function __construct(
        string $id,
        string $name,
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public static function create(string $id, string $name): static
    {
        return new static($id, $name);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function users(): array
    {
        return $this->users;
    }
}
