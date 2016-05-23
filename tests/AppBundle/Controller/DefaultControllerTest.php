<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Posts', $crawler->filter('#totalNumberOfPosts')->text());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Export', $crawler->filter('#exportButton')->text());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Views', $crawler->filter('#totalNumberOfViews')->text());
    }
}
