<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use TicketSystem\Shared\Domain\Email;

class DoctrineEmail extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof Email ? $value->value : (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Email
    {
        return Email::create((string) $value);
    }
}
