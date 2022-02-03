<?php

namespace App\Entity;

class BillingProfile
{
    private function __construct(
        private readonly string $id,
        private readonly Organization $organization,
    ) {
    }

    public static function create(string $id, Organization $organization): static
    {
        return new static($id, $organization);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function organization(): Organization
    {
        return $this->organization;
    }
}
