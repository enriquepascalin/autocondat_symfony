<?php

declare(strict_types=1);

namespace App\Contracts;

interface SoftDeletableInterface
{
    /**
     * Get the deletion timestamp.
     */
    public function getDeletedAt(): ?\DateTimeInterface;

    /**
     * Set the deletion timestamp.
     */
    public function setDeletedAt(?\DateTimeInterface $deletedAt): self;

    /**
     * Check if the entity is deleted.
     */
    public function isDeleted(): bool;
}
