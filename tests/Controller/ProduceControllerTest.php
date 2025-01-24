<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProduceControllerTest extends WebTestCase
{
    public function testListProduceIsAccesible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/produce');

        self::assertResponseIsSuccessful();
    }
}
