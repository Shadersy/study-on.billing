<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class BillingTest extends WebTestCase
{
    public function testSCurrentUser(): void
    {
        $client = static::createClient();

        $arr = array(['email'=>'test12user@mail.ru', 'password' => 'qweasd']);


        $client->request('POST', 'api/v1/register', $arr, [],
            ['Content-Type' => 'application/json']);

        dump($client->getResponse()->getContent());
    }
}
