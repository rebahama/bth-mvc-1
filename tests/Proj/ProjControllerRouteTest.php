<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjControllerRouteTest extends WebTestCase
{
    public function testProjHomeRouteIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Välkommen');
    }

    public function testProjAboutRouteIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/about');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Om denna sida');
    }

     public function testProjBrandsRouteIsSuccessful(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/proj/brands');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('h1', 'Alla märken');
    }
}