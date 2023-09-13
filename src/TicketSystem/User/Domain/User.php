<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

use TicketSystem\Shared\Domain\Aggregate;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\User\Domain\Operator\NotOperatorException;
use TicketSystem\User\Domain\Operator\Operator;
use TicketSystem\User\Domain\Operator\OperatorId;
use TicketSystem\User\Domain\Operator\OperatorMustBeAssignedToCategoryException;

class User implements Aggregate
{
    public const NAME_MAX_LENGTH = 64;
    public const LASTNAME_MAX_LENGTH = 64;

    private null|Operator $operator = null;

    private function __construct(
        public readonly UserId $id,
        public readonly Email $email,
        public readonly string $name,
        public readonly string $lastname,
        #[\SensitiveParameter] private readonly string $password,
        private UserRole $role
    ) {
        $this->validate($password, $name, $lastname);
    }

    private function validate(string $password, string $name, string $lastname): void
    {
        if (!$password) {
            throw new \InvalidArgumentException('The password must not be empty');
        }

        if ('' === $name || strlen($name) > self::NAME_MAX_LENGTH) {
            throw new \InvalidArgumentException(
                sprintf('Name must not be empty and not longer than %d chars', self::NAME_MAX_LENGTH)
            );
        }

        if ('' === $lastname || strlen($lastname) > self::LASTNAME_MAX_LENGTH) {
            throw new \InvalidArgumentException(
                sprintf('Lastname must not be empty and not longer than %d chars', self::NAME_MAX_LENGTH)
            );
        }
    }

    public static function create(
        UserId $id,
        Email $email,
        string $name,
        string $lastname,
        #[\SensitiveParameter] string $password,
        UserRole $role
    ): self {
        return new self($id, $email, $name, $lastname, $password, $role);
    }

    public function isEqual(User $user): bool
    {
        return $this->id->isEqual($user->id);
    }

    public function password(): string
    {
        return $this->password;
    }

    public function role(): UserRole
    {
        return $this->role;
    }

    public function become(UserRole $role, null|TicketCategory $ticketCategory = null): self
    {
        $this->role = $role;

        if (in_array($role, UserRole::operatorRoles())) {
            $this->becomeOperator($role, $ticketCategory);
        }

        return $this;
    }

    private function becomeOperator(UserRole $role, null|TicketCategory $ticketCategory = null): void
    {
        if (UserRole::OPERATOR === $role && null === $ticketCategory) {
            throw OperatorMustBeAssignedToCategoryException::create();
        }

        $this->operator = Operator::create(
            OperatorId::create($this->id->id),
            $this,
            $this->isSuperOperator() ? null : $ticketCategory
        );
    }

    public function isOperator(): bool
    {
        return null !== $this->operator;
    }

    public function isSuperOperator(): bool
    {
        return $this->isOperator() && UserRole::SUPER_OPERATOR === $this->role;
    }

    public function operatorCategory(): null|TicketCategory
    {
        return $this->operatorOrFail()->assignedCategory();
    }

    private function operatorOrFail(): Operator
    {
        if (null === $this->operator) {
            throw NotOperatorException::create($this->id);
        }

        return $this->operator;
    }
}
