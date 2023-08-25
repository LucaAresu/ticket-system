<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use TicketSystem\Shared\Domain\EntityId;

abstract class DoctrineEntityId extends GuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): null|string
    {
        if (null === $value) {
            return null;
        }

        return $value instanceof EntityId ? $value->id : (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): null|EntityId
    {
        if (null === $value) {
            return null;
        }
        $className = $this->getFQCN();

        return $className::create((string) $value);
    }

    /**
     * @return class-string<EntityId>
     */
    abstract protected function getFQCN(): string;
}
