<?php

namespace App\Contracts;

use DateTimeInterface;

/**
 * Interface TimestampableInterface
 *
 * This interface defines methods for timestampable entities.
 */
interface TimestampableInterface    
{
    /**
     * Get the creation timestamp.
     *
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface;

    /**
     * Set the creation timestamp.
     *
     * @param DateTimeInterface|null $createdAt
     * @return self
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): self;

    /**
     * Get the last update timestamp.
     *
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface;

    /**
     * Set the last update timestamp.
     *
     * @param DateTimeInterface|null $updatedAt
     * @return self
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): self;
}