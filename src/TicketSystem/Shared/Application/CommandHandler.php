<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Application;

use TicketSystem\Shared\Domain\DomainException;

/**
 * @template I
 * @template O
 *
 * @psalm-suppress UnusedClass
 */
readonly class CommandHandler
{
    /**
     * @param Command<I, O> $command
     */
    public function __construct(private Command $command)
    {
    }

    /**
     * @param I $request
     *
     * @return CommandFailedResponse|O
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function execute($request)
    {
        try {
            return $this->command->execute($request);
        } catch (\InvalidArgumentException $e) {
            return CommandFailedResponse::create(
                sprintf('A validation error occurred: %s', $e->getMessage()),
                401
            );
        } catch (DomainException $e) {
            CommandFailedResponse::create($e->getMessage(), 500);
        } catch (\Throwable $e) {
            // todo notify
            return CommandFailedResponse::create('Internal Error', 500);
        }
    }
}
