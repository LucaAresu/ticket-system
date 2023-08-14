<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ProvaTest extends TestCase
{
    /**
     * @test
     */
    public function it_shoult_boh()
    {
        $c = new \TicketSystem\Ticket\Infrastructure\Communication\Http\Symfony\Controller\TicketController();

        $c->create();
        self::assertTrue(true);
    }
}
