<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Domain;

/**
 * @template-covariant E of Aggregate
 * @template-covariant I of EntityId
 */
interface Repository
{
    /**
     * @return null|E
     */
    public function ofId(EntityId $id): null|Aggregate;

    /**
     * @return I
     */
    public function nextId(): EntityId;

    public function save(Aggregate $aggregate): void;
}
