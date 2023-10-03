<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Domain;

readonly class EntityId
{
    private const PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';

    public string $id;

    final private function __construct(string $value)
    {
        if (false === static::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('Invalid ID: %s', $value));
        }

        $this->id = $value;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public static function create(string $value): static
    {
        return new static($value);
    }

    public static function isValid(string $value): bool
    {
        return (bool) preg_match(self::PATTERN, $value);
    }

    public function isEqual(null|EntityId $entityId): bool
    {
        if (null === $entityId) {
            return false;
        }

        return static::class === $entityId::class
            && $this->id === $entityId->id;
    }
}
