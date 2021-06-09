<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiServiceTest extends WebTestCase {

    use FixturesTrait;

    private function setFixtures()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\CourseFixture', 'App\DataFixtures\UserFixture', 'App\DataFixtures\WTransactionFixture'
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

        $this->assertStringContainsString(
            'Invalid credentials',
            $data
        );
    }

    public function doAuth(Object $client) : string
    {
        $this->setFixtures();
        $client->request(
            'POST',
            'api/v1/auth',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            '{"username":"admin@mail.ru", "password":"qwerty"}'
        );
        $data = json_decode($client->getResponse()->getContent());


        $userToken = $data->token;

        return $userToken;
    }

    public function testBuyingCourse() : void
    {
        $client = static::createClient();

        $userToken = $this->doAuth($client);

        $client->request('POST', '/api/v1/courses/testCourse/pay', [], [], ['HTTP_AUTHORIZATION' => 'Bearer '. $userToken]);
        $data = $client->getResponse();

        //покупаем арендуемый курс
        $this->assertStringContainsString(
            'success":"true',
            $data
        );

        //пытаемся купить повторно
        $client->request('POST', '/api/v1/courses/testCourse/pay', [], [], ['HTTP_AUTHORIZATION' => 'Bearer '. $userToken]);
        $data = $client->getResponse()->getContent();
        $this->assertStringContainsString(
            'code":"406',
            $data
        );
    }

    public function testDeposite() : void
    {
        $client = static::createClient();

        $userToken = $this->doAuth($client);

        $client->request('POST', '/api/v1/deposite/100', [], [], ['HTTP_AUTHORIZATION' => 'Bearer '. $userToken]);
        $data = $client->getResponse();

        $this->assertStringContainsString(
            'success":"true',
            $data
        );

        //нижняя граница
        $client->request('POST', '/api/v1/deposite/0', [], [], ['HTTP_AUTHORIZATION' => 'Bearer '. $userToken]);
        $data = $client->getResponse();

        $this->assertStringContainsString(
            '"code":"406"',
            $data
        );

        //верхняя граница
        $client->request('POST', '/api/v1/deposite/100000', [], [], ['HTTP_AUTHORIZATION' => 'Bearer '. $userToken]);
        $data = $client->getResponse();

        $this->assertStringContainsString(
            '"code":"406"',
            $data
        );
    }

    public function testUserBalance() : void
    {
        $client = static::createClient();

        $userToken = $this->doAuth($client);

        $client->request('POST', '/api/v1/current', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken]);
        $data = $client->getResponse()->getContent();

        $this->assertStringContainsString(
            '"balance"',
            $data
        );
    }

    public function testTransactionsApi() : void
    {
        $client = static::createClient();

        $userToken = $this->doAuth($client);

        $client->request('GET', '/api/v1/transactions', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken]);
        $data = $client->getResponse()->getContent();

        //показать все имеющиеся транзакции пользователя
        $validJson = '[{"id":1,"type":"payment",
        "course_code":"barber-muzhskoy-parikmaher",
        "created_at":{"date":"2021-10-17 00:00:00.000000",
        "timezone_type":3,"timezone":"UTC"},
        "amount":-0},
        {"id":2,"type":"payment","course_code":"gosudarstvenno-chastnoe-partnerstv",
        "created_at":{"date":"2021-10-17 00:00:00.000000","timezone_type":3,"timezone":"UTC"},
        "amount":-20},{"id":3,"type":"payment","course_code":"landshaftnoe-proektirovanie",
        "created_at":{"date":"2021-10-17 00:00:00.000000","timezone_type":3,"timezone":"UTC"},
        "amount":-30.9},{"id":4,"type":"deposite","course_code":" ","created_at":{"date":"2021-10-17 00:00:00.000000",
        "timezone_type":3,"timezone":"UTC"},"amount":"+50"}]';

        $this->assertJsonStringEqualsJsonString($validJson, $data);

        //поиск конкретного курса в транзакции
        $client->request('GET', '/api/v1/transactions?filter[course]=barber-muzhskoy-parikmaher', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken]);
        $data = $client->getResponse()->getContent();
        $barberCourseJson = '[{"id":1,"type":"payment",
        "course_code":"barber-muzhskoy-parikmaher",
        "created_at":{"date":"2021-10-17 00:00:00.000000",
        "timezone_type":3,"timezone":"UTC"},"amount":5000}]';
        $this->assertJsonStringEqualsJsonString($barberCourseJson, $data);

        //показать только зачисления
        $client->request('GET', '/api/v1/transactions?filter[type]=deposite', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken]);
        $data = $client->getResponse()->getContent();
        $depositeTransactioJson = '[{"id":4,"type":"deposite",
        "course_code":" ","created_at":{"date":"2021-10-17 00:00:00.000000","timezone_type":3,
        "timezone":"UTC"},"amount":"+50"}]';
        $this->assertJsonStringEqualsJsonString($depositeTransactioJson, $data);
    }

    public function testMethodCourses()
    {
        $client = static::createClient();

        $userToken = $this->doAuth($client);

        $client->request('GET', '/api/v1/courses', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken]);
        $data = $client->getResponse()->getContent();
        $validJson = '{"barber-muzhskoy-parikmaher":{"code":"barber-muzhskoy-parikmaher",
        "email":"admin@mail.ru",
        "cost":"0","type":0,"validity":null},
        "gosudarstvenno-chastnoe-partnerstv":{"code":"gosudarstvenno-chastnoe-partnerstv",
        "email":"admin@mail.ru","cost":"20","type":1,"validity":null},
        "landshaftnoe-proektirovanie":{"code":"landshaftnoe-proektirovanie",
        "email":"admin@mail.ru","cost":"30.9","type":2,"validity":"2021-10-17 00:00:00"},
        "samiy-dorogoi-kurs":{"code":"samiy-dorogoi-kurs","id":2,"type":2,"cost":10000,
        "email":null,"validity":null},"testCourse":{"code":"testCourse","id":5,"type":2,
        "cost":400,"email":null,"validity":null}}';

        $this->assertJsonStringEqualsJsonString($validJson, $data);
    }

    public function testCreatingNewCourse() {
        $client = static::createClient();

        $userToken = $this->doAuth($client);

        //создание платного курса
        $client->request('POST', '/api/v1/courses', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"1", "title":"test", "code":"test123", "price":"100"}');
        $data = $client->getResponse()->getContent();

        $this->assertStringContainsString(
            '{"success":"true"}',
            $data
        );

        //создание бесплатного курса
        $client->request('POST', '/api/v1/courses', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"0", "title":"freeCourse", "code":"free123", "price":"0"}');
        $data = $client->getResponse()->getContent();

        $this->assertStringContainsString(
            '{"success":"true"}',
            $data
        );

        //создание бесплатного курса с попыткой указать цену
        $client->request('POST', '/api/v1/courses', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"0", "title":"test", "code":"test123", "price":"100"}');
        $data = $client->getResponse()->getContent();

        $this->assertStringContainsString(
            '"code":"406"',
            $data
        );

        //создание платного курса без стоимости
        $client->request('POST', '/api/v1/courses', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"0", "title":"test", "code":"test123", "price":"0"}');
        $data = $client->getResponse()->getContent();

        $this->assertStringContainsString(
            '"code":"406"',
            $data
        );

        //попытка создать курс с уже имеющимся кодом в бд
        $client->request('POST', '/api/v1/courses', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"0", "title":"freeCourse", "code":"barber-muzhskoy-parikmaher", "price":"0"}');
        $data = $client->getResponse()->getContent();


        //попытка создать курс с отрицательной ценой
        $client->request('POST', '/api/v1/courses', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"2", "title":"negativePrice", "code":"negativePrice", "price":"-1"}');
        $data = $client->getResponse()->getContent();

        $this->assertStringContainsString(
            '"code":"406"',
            $data
        );
    }

    public function testEditCourse() {
        $client = static::createClient();

        $userToken = $this->doAuth($client);

        //меняем только тип курса с бесплатного на аренду (соответственно и цену)
        $client->request('POST', '/api/v1/courses/barber-muzhskoy-parikmaher', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"2", "title":"barber-muzhskoy-parikmaher", "code":"barber-muzhskoy-parikmaher", "price":"200"}');
        $data = $client->getResponse()->getContent();
        $this->assertStringContainsString('{"success":"true"}', $data);


        //меняем код курса на уникальный
        $client->request('POST', '/api/v1/courses/barber-muzhskoy-parikmaher', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"2", "title":"barber-muzhskoy-parikmaher", "code":"newCourse", "price":"200"}');
        $data = $client->getResponse()->getContent();
        $this->assertStringContainsString('{"success":"true"}', $data);

        //меняем код курса на неуникальный
        $client->request('POST', '/api/v1/courses/newCourse', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"2", "title":"barber-muzhskoy-parikmaher", "code":"landshaftnoe-proektirovanie", "price":"200"}');
        $data = $client->getResponse()->getContent();
        $this->assertStringContainsString('"code":"406"', $data);

        //меняем код курса на уникальный ещё раз, изменим цену и тип курса
        $client->request('POST', '/api/v1/courses/newCourse', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"0", "title":"barber-muzhskoy-parikmaher", "code":"unique", "price":"0"}');
        $data = $client->getResponse()->getContent();
        $this->assertStringContainsString('{"success":"true"}', $data);
    }

    public function testSimpleUser()
    {
        $client = static::createClient();

        $this->setFixtures();
        $client->request(
            'POST',
            'api/v1/auth',
            [],
            [],
            array('CONTENT_TYPE' => 'application/json'),
            '{"username":"shadersy98@mail.ru", "password":"qwerty"}'
        );
        $data = json_decode($client->getResponse()->getContent());


        $userToken = $data->token;

       //пробуем создать курс не админом
        $client->request('POST', '/api/v1/courses', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"0", "title":"freeCourse", "code":"free123", "price":"0"}');
        $data = $client->getResponse();
        $this->assertStringNotContainsString('{"success":"true"}', $data);

        //изменить существующий
        $client->request('POST', '/api/v1/courses/barber-muzhskoy-parikmaher', [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $userToken,
            'CONTENT_TYPE' => 'application/json'],
            '{"type":"2", "title":"barber-muzhskoy-parikmaher", "code":"barber-muzhskoy-parikmaher", "price":"200"}');
        $data = $client->getResponse()->getContent();
        $this->assertStringNotContainsString('{"success":"true"}', $data);
    }
}