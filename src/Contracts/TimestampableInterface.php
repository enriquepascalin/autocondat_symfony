<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Interface TimestampableInterface.
 *
 * This interface defines methods for timestampable entities.
 */
interface TimestampableInterface
{
    /**
     * Get the creation timestamp.
     */
    public function getCreatedAt(): ?\DateTimeInterface;

    /**
     * Set the creation timestamp.
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt): self;

    /**
     * Get the last update timestamp.
     */
    public function getUpdatedAt(): ?\DateTimeInterface;

    /**
     * Set the last update timestamp.
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self;
}
