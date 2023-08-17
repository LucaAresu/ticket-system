<?php

declare(strict_types=1);

namespace Tests\Helpers\User\Domain;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use TicketSystem\User\Application\CreateUser\CreateUserCommand;
use TicketSystem\User\Application\CreateUser\CreateUserCommandRequest;
use TicketSystem\User\Infrastructure\Domain\Symfony\Security\DoctrineSecurityUser;
use TicketSystem\User\Infrastructure\Symfony\Security\AccessToken\AccessTokenRepository;

trait CreateUserTrait
{
    protected function createUser(): void
    {
        /** @var CreateUserCommand $createUserCommand */
        $createUserCommand = self::getContainer()->get(CreateUserCommand::class);
        $createUserCommand->execute(
            CreateUserCommandRequest::create(
                UserHelper::userId(),
                UserHelper::email(),
                UserHelper::password()
            )
        );
    }

    protected function createAndLoginUser(KernelBrowser $client): void
    {
        $this->createUser();

        /** @var AccessTokenRepository $accessTokenRepository */
        $accessTokenRepository = self::getContainer()->get(AccessTokenRepository::class);
        $accessTokenRepository->save('access-token', UserHelper::userId());

        $client->loginUser(new DoctrineSecurityUser(UserHelper::userId()));
    }
}
