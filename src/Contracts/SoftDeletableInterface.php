<?php

namespace App\Contracts;

use DateTimeInterface;

interface SoftDeletableInterface
{
    /**
     * Get the deletion timestamp.
     *
     * @return DateTimeInterface|null
     */
    public function getDeletedAt(): ?DateTimeInterface;

    /**
     * Set the deletion timestamp.
     *
     * @param DateTimeInterface|null $deletedAt
     * @return self
     */
    public function setDeletedAt(?DateTimeInterface $deletedAt): self;

    /**
     * Check if the entity is deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool;
}