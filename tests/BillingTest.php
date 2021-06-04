<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class BillingTest extends WebTestCase
{
    use FixturesTrait;

    private function setFixtures()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\CourseFixture', 'App\DataFixtures\UserFixture'
        ));
    }

    public function testCorrectRegisterUser(): void
    {
        $client = static::createClient();
        $this->setFixtures();

        //тест на валидные значения
        $arr = json_encode(array('email' => 'user@mail.ru', 'password' => 'qweasd'));

        $client->request('POST', 'api/v1/register', ['Content-Type' => 'application/json'], [], [], $arr);
        $data = $client->getResponse()->getContent();


        $this->assertStringContainsString(
            '"token":',
            $data
        );

        $this->assertStringContainsString(
            '"refreshToken":',
            $data
        );
    }

    public function testInvalidEmailRegisterUser(): void
    {
        $client = static::createClient();

        //тест некорректный эмейл
        $arr = json_encode(array('email' => 'usermail.ru', 'password' => 'qweasd'));

        $client->request('POST', 'api/v1/register', ['Content-Type' => 'application/json'], [], [], $arr);
        $data = $client->getResponse()->getContent();


        $this->assertStringContainsString(
            'Email is not written correctly',
            $data
        );
    }

    public function testInvalidPassRegisterUser(): void
    {
        $client = static::createClient();

        //тест некорректный эмейл
        $arr = json_encode(array('email' => 'user@mail.ru', 'password' => 'qwe'));

        $client->request('POST', 'api/v1/register', ['Content-Type' => 'application/json'], [], [], $arr);
        $data = $client->getResponse()->getContent();


        $this->assertStringContainsString(
            'Password must be longer than 6 characters',
            $data
        );
    }

    public function testInvalidLongPassword(): void
    {
        $client = static::createClient();
        $longPass = "";
        //тест некорректный эмейл
        for ($i = 0; $i < 20; $i++) {
            $longPass .= 'qweasd';
        }

        $arr = json_encode(array('email' => 'user@mail.ru', 'password' => $longPass));

        $client->request('POST', 'api/v1/register', ['Content-Type' => 'application/json'], [], [], $arr);
        $data = $client->getResponse()->getContent();

        $this->assertStringContainsString(
            ' Password length must be less than 40 characters',
            $data
        );
    }

    public function testCheckUniqueRegisterUser(): void
    {
        $client = static::createClient();
        $this->setFixtures();
        //тест некорректный эмейл
        $arr = json_encode(array('email' => 'admin@mail.ru', 'password' => 'qweasd'));

        $client->request('POST', 'api/v1/register', ['Content-Type' => 'application/json'], [], [], $arr);
        $data = $client->getResponse()->getContent();
        $this->assertStringContainsString(
            'This value is already used',
            $data
        );
    }

    public function testAuthUser(): void
    {
        $client = static::createClient();
        $this->setFixtures();
        $client->request(
            'POST',
            'api/v1/auth',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            '{"username":"admin@mail.ru", "password":"qwerty"}'
        );
        $data = $client->getResponse();

        $this->assertStringContainsString(
            '"token":',
            $data
        );

        $this->assertStringContainsString(
            '"refresh_token":',
            $data
        );
    }

    public function testAuthUnExistedUser(): void
    {
        $client = static::createClient();
        $this->setFixtures();
        $client->request(
            'POST',
            'api/v1/auth',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            '{"username":"blank@mail.ru", "password":"qwerty"}'
        );
        $data = $client->getResponse();

        var_dump($data);
        $this->assertStringContainsString(
            'Invalid credentials',
            $data
        );
    }
}