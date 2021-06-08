<?php
namespace App\DataFixtures;
use App\Entity\Transaction;
use App\Repository\CourseRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;

class WTransactionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $courseRepo = $manager->getRepository('App\Entity\Course');
        $userRepo = $manager->getRepository('App\Entity\User');

        $barberCourse = $courseRepo->findOneBy(['symbolCode' => "barber-muzhskoy-parikmaher"]);
        $gosCourse = $courseRepo->findOneBy(['symbolCode' => "gosudarstvenno-chastnoe-partnerstv"]);
        $landshaftCourse = $courseRepo->findOneBy(['symbolCode' => "landshaftnoe-proektirovanie"]);

        $adminUser = $userRepo->findOneBy(['email' => 'admin@mail.ru']);

        $transactionOne = new Transaction();
        $transactionOne->setCourse($barberCourse);
        $transactionOne->setUsername($adminUser);
        $transactionOne->setCreatedAt(new \DateTime());
        $transactionOne->setOperationType($barberCourse->getCourseType());
        $transactionOne->setValue($barberCourse->getCost());

        $transactionTwo = new Transaction();
        $transactionTwo->setCourse($gosCourse);
        $transactionTwo->setUsername($adminUser);
        $transactionTwo->setCreatedAt(new \DateTime());
        $transactionTwo->setOperationType($gosCourse->getCourseType());
        $transactionTwo->setValue($gosCourse->getCost());


        $transactionThird = new Transaction();
        $transactionThird->setCourse($landshaftCourse);
        $transactionThird->setUsername($adminUser);
        $transactionThird->setCreatedAt(new \DateTime());
        $transactionThird->setOperationType($landshaftCourse->getCourseType());
        $transactionThird->setValue($landshaftCourse->getCost());
        $transactionThird->setEndOfRent((new \DateTime())->modify('+1 week'));

        $manager->persist($transactionOne);
        $manager->persist($transactionTwo);
        $manager->persist($transactionThird);
        $manager->flush();
    }

}