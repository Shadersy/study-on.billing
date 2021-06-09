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
        $universalDate = new \DateTime('10-10-2021');

        $courseRepo = $manager->getRepository('App\Entity\Course');
        $userRepo = $manager->getRepository('App\Entity\User');

        $barberCourse = $courseRepo->findOneBy(['symbolCode' => "barber-muzhskoy-parikmaher"]);
        $gosCourse = $courseRepo->findOneBy(['symbolCode' => "gosudarstvenno-chastnoe-partnerstv"]);
        $landshaftCourse = $courseRepo->findOneBy(['symbolCode' => "landshaftnoe-proektirovanie"]);

        $adminUser = $userRepo->findOneBy(['email' => 'admin@mail.ru']);

        $transactionOne = new Transaction();
        $transactionOne->setCourse($barberCourse);
        $transactionOne->setUsername($adminUser);

        $transactionOne->setCreatedAt($universalDate);
        $transactionOne->setOperationType(0);
        $transactionOne->setValue($barberCourse->getCost());

        $transactionTwo = new Transaction();
        $transactionTwo->setCourse($gosCourse);
        $transactionTwo->setUsername($adminUser);
        $transactionTwo->setCreatedAt($universalDate);
        $transactionTwo->setOperationType(0);
        $transactionTwo->setValue($gosCourse->getCost());


        $transactionThird = new Transaction();
        $transactionThird->setCourse($landshaftCourse);
        $transactionThird->setUsername($adminUser);
        $transactionThird->setCreatedAt($universalDate);
        $transactionThird->setOperationType(0);
        $transactionThird->setValue($landshaftCourse->getCost());
        $transactionThird->setEndOfRent(($universalDate)->modify('+1 week'));


        $depositeTransaction = new Transaction();
        $depositeTransaction->setUsername($adminUser);
        $depositeTransaction->setCreatedAt($universalDate);
        $depositeTransaction->setOperationType(1);
        $depositeTransaction->setValue(50);


        $manager->persist($transactionOne);
        $manager->persist($transactionTwo);
        $manager->persist($transactionThird);
        $manager->persist($depositeTransaction);
        $manager->flush();
    }
}
