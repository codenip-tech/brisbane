<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @method string getUserIdentifier()
 */
#[ORM\Entity]
class User implements UserInterface
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
            Organization::create(Uuid::v4()->toRfc4122(), \sprintf('%s\'s organization', $email)),
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

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function organizations(): array
    {
        return $this->organizations;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {

    }

    public function getSalt()
    {

    }

    public function eraseCredentials()
    {

    }

    public function getUsername()
    {
        return $this->getUserIdentifier();
    }

    public function __call(string $name, array $arguments)
    {
        if ($name !== 'getUserIdentifier') {
            throw new \LogicException(sprintf('Received method %s but expected getUserIdentifier', $name));
        }
        return $this->email();
    }
}
