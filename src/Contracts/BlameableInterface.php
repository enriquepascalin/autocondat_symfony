<?php

namespace App\Contracts;

use App\Entity\AuthenticationModule\User;

interface BlameableInterface
{
    /**
     * Get the user who created the entity.
     *
     * @return User|null
     */
    public function getCreatedBy(): ?User;

    /**
     * Set the user who created the entity.
     *
     * @param User|null $createdBy
     * @return self
     */
    public function setCreatedBy(?User $createdBy): self;

    /**
     * Get the user who last updated the entity.
     *
     * @return User|null
     */
    public function getUpdatedBy(): ?User;

    /**
     * Set the user who last updated the entity.
     *
     * @param User|null $updatedBy
     * @return self
     */
    public function setUpdatedBy(?User $updatedBy): self;
}