<?php

declare(strict_types=1);

namespace Tests\Helpers\User;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use TicketSystem\User\Domain\CreateUser\CreateUser;
use TicketSystem\User\Domain\CreateUser\CreateUserRequest;
use TicketSystem\User\Infrastructure\Domain\Symfony\Security\DoctrineSecurityUser;
use TicketSystem\User\Infrastructure\Symfony\Security\AccessToken\AccessTokenRepository;

trait CreateUserTrait
{
    protected function createUser(): void
    {
        /** @var CreateUser $createUserCommand */
        $createUserCommand = self::getContainer()->get(CreateUser::class);
        $createUserCommand->execute(
            CreateUserRequest::create(
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
        $accessTokenRepository = self::getContainer()->get('TestAccessTokenRepository');
        $accessTokenRepository->save('access-token', UserHelper::userId());

        $client->loginUser(new DoctrineSecurityUser(UserHelper::userId()));
    }
}
