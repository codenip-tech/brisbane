<?php

namespace App\Domain\Entity;

class BillingProfile
{
    private function __construct(
        private readonly string $ids,
        private readonly Organization $organization,
    ) {
    }

    public static function create(string $id, Organization $organization): static
    {
        return new static($id, $organization);
    }

    public function id(): string
    {
        return $this->ids;
    }

    public function organization(): Organization
    {
        return $this->organization;
    }
}
