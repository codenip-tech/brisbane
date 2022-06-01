<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Organization
{
    private readonly string $id;

    private string $name;

    /** @var User[] */
    private Collection $users;

    private function __construct(
        string $id,
        string $name,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->users = new ArrayCollection();
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
