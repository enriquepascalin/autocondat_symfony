<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Entity\AuthenticationModule\User;

interface BlameableInterface
{
    /**
     * Get the user who created the entity.
     */
    public function getCreatedBy(): ?User;

    /**
     * Set the user who created the entity.
     */
    public function setCreatedBy(?User $createdBy): self;

    /**
     * Get the user who last updated the entity.
     */
    public function getUpdatedBy(): ?User;

    /**
     * Set the user who last updated the entity.
     */
    public function setUpdatedBy(?User $updatedBy): self;
}
