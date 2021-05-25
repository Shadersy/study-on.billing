<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class BillingTest extends WebTestCase
{
    public function testRegisterUser(): void
    {
        $client = static::createClient();

        $arr = array('email'=>'test123user@mail.ru', 'password' => 'qweasd');
        $json = json_encode($arr);



        $client->request('POST', 'api/v1/register', ['Content-Type:' => 'application/json'], [], [], $json
           );

        $data = $client->getResponse()->getContent();

        $this->assertStringContainsString(
            '"token":',
            $data
        );

        $this->assertStringContainsString(
            '"refresh_token":',
            $data
        );

        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $purger = new ORMPurger($em);
        $purger->purge();

    }

    public function testAuthUser(): void
    {
        $client = static::createClient();

        $arr = array('username'=>'test123user@mail.ru', 'password' => 'qweasd');
        $json = json_encode($arr);

        $client->request('POST', '/api/v1/auth', ['Content-Type:' => 'application/json'], [], [], $json
        );

        $client->getResponse();
    }
}
