<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Course;


class CourseFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $barberCourse = new Course();
        $barberCourse->setTitle('Барбер-мужской парикмахер');
        $barberCourse->setSymbolCode('barber-muzhskoy-parikmaher');
        $barberCourse->setCourseType(0);
        $barberCourse->setCost(0);




        $guitarCourse = new Course();
        $guitarCourse->setTitle('Гитарный профи');
        $guitarCourse->setSymbolCode('samiy-dorogoi-kurs');
        $guitarCourse->setCourseType(2);
        $guitarCourse->setCost(10000);




        $gosCourse = new Course();
        $gosCourse->setTitle('Государственно-частное партнерство');
        $gosCourse->setSymbolCode('gosudarstvenno-chastnoe-partnerstv');
        $gosCourse->setCost(20);
        $gosCourse->setCourseType(1);




        $landshaftCourse = new Course();
        $landshaftCourse->setTitle('Ландшафтное проектирование');
        $landshaftCourse->setSymbolCode('landshaftnoe-proektirovanie');
        $landshaftCourse->setCourseType(2);
        $landshaftCourse->setCost(30.9);



        $testCourse = new Course();
        $testCourse->setTitle('Тестовый курс');
        $testCourse->setSymbolCode('testCourse');
        $testCourse->setCost(400);
        $testCourse->setCourseType(2);



        $manager->persist($barberCourse);
        $manager->persist($guitarCourse);
        $manager->persist($gosCourse);
        $manager->persist($landshaftCourse);
        $manager->persist($testCourse);


        $manager->flush();
    }
}
