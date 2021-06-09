<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }



    public function getCoursesByUser(
        string $username,
        EntityManager $em
    ) {
        $connection = $em->getConnection();
        $statement = $connection->prepare('SELECT DISTINCT  symbol_code, course_type, cost, email, end_of_rent
            FROM course LEFT JOIN transaction t on course.id = t.course_id
            LEFT JOIN billing_user bu on t.username_id = bu.id WHERE email = :username');

        $statement->bindValue('username', $username);
        $statement->execute();
        $results = $statement->fetchAll();


        return $results;
    }
}
