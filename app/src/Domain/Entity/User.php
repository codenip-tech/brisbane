<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class User
{
    private string $id;

    private string $email;

    /** @var Organization[] */
    private Collection $organizations;

    public function __construct(string $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
        $this->organizations = new ArrayCollection([
            Organization::create(Uuid::v4()->toRfc4122(), \sprintf('%s\'s organization', $email))
        ]);
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
