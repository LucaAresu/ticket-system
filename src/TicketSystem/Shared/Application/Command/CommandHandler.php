<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Application\Command;

use TicketSystem\Shared\Application\Logger;
use TicketSystem\Shared\Domain\DomainException;

/**
 * @template I
 * @template O
 */
readonly class CommandHandler
{
    /**
     * @param Command $command
     */
    public function __construct(private Command $command, private Logger $logger)
    {
    }

    /**
     * @param I $request
     *
     * @return CommandFailureResponse|O
     */
    public function execute($request)
    {
        try {
            /** @var O $response */
            $response = $this->command->execute($request);

            return $response;
        } catch (\InvalidArgumentException $e) {
            return CommandFailureResponse::create(
                sprintf('A validation error occurred: %s', $e->getMessage()),
                401
            );
        } catch (DomainException $e) {
            $this->logger->error($e->getMessage(), [$e]);

            return CommandFailureResponse::create($e->getMessage(), 500);
        } catch (\Throwable $e) {
            $this->logger->critical($e->getMessage(), [$e]);

            return CommandFailureResponse::create('Internal Error', 500);
        }
    }
}
