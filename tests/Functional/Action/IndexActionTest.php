<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexActionTest extends WebTestCase
{
    private const API_VERSION = '1.2';

    public function testIndexActionTypes(): void
    {
        $client = static::createClient();

        $client->request('GET', sprintf('/api/%s', self::API_VERSION));
        $this->assertResponseIsSuccessful();

        $response = $client->getResponse()->getContent();
        $data = json_decode($response);

        $this->assertSame(true, $data->status);
    }
}