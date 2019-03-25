<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testEditpost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'edit_post');
    }

    public function testDeletepost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'delete_post');
    }

}
