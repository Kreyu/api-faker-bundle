<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EndpointAvailabilityTest extends WebTestCase
{
    public function testEndpointWithCustomMethod()
    {
        $client = self::createClient();
        $client->request('POST', '/test-api/endpoint-with-custom-method');

        $this->assertResponseIsSuccessful();
    }

    public function testEndpointWithCustomResponseStatusCode()
    {
        $client = self::createClient();
        $client->request('GET', '/test-api/endpoint-with-custom-response-status-code');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

    public function testEndpointWithCustomResponseContent()
    {
        $client = self::createClient();
        $client->request('GET', '/test-api/endpoint-with-custom-response-content');

        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();

        $body = json_encode([
            'foo' => 'bar',
            'lorem' => 'ipsum',
        ]);

        $this->assertSame($body, $response->getContent());
    }
}
