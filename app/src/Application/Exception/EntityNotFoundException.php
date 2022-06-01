<?php

declare(strict_types=1);

namespace App\Application\Exception;

class EntityNotFoundException extends \DomainException
{
    public static function fromClassAndId(string $class, string $id): self
    {
        return new self(\sprintf('Entity [%s] with id [%s] not found', $class, $id));
    }
}
