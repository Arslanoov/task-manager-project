<?php

declare(strict_types=1);

namespace Tests\Functional\Auth;

use Tests\Functional\FunctionalTestCase;

class SignUpTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            RequestFixture::class,
        ]);

        parent::setUp();
    }

    public function testMethod(): void
    {
        $response = $this->get('/api/auth/signup');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->post('/api/auth/signup', [
            'login' => 'user',
            'email' => 'mail@email.com',
            'password' => 'password'
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        $this->assertEquals([
            'email' => 'mail@email.com',
        ], $data);
    }
}