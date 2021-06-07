<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function endingNotification()
    {

        $qb = $this->createQueryBuilder('t')
            ->where('t.endOfRent - :current_time < \'23:59:59\' AND t.endOfRent > :current_time')
            ->setParameter('current_time', new \DateTime());

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function reportMonth()
    {
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare('SELECT  c.title,
        count(c.cost), c.course_type, sum(c.cost)
        FROM transaction LEFT JOIN course c on transaction.course_id = c.id
        LEFT JOIN billing_user bu on transaction.username_id = bu.id WHERE transaction.created_at >  \'now\'::timestamp - \'1 month\'::interval
        GROUP BY c.title, c.course_type');
        $resultSet = $stmt->execute();

        $result = [];
        while (($row = $stmt->fetchAssociative()) !== false) {
          $result[]=$row;
        }

        return $result;
    }

    public function filterTransaction(
        User $user,
        array $filters,
        ?Course $course)
    {

        $filterType = null;
        $filterExpired = null;


        if ($filters) {
            if (array_key_exists('type', $filters['filter'])) {

                $filterType = $filters['filter']['type']; //тип транзакции payment|deposit
            }

            if (array_key_exists('skipexpired', $filters['filter'])) {
                $filterExpired = $filters['filter']['skipexpired']; //флаг, позволяющий отбросить записи с датой
                // expires_at в прошлом (т.е. оплаты аренд, которые уже истекли).
            }
        }


        $qb = $this->createQueryBuilder('t')
            ->where('t.username = :username')
            ->setParameter('username', $user->getId());

        if ($course) {
            $qb->andWhere('t.course = :course')
                ->setParameter('course', $course);
        }

        if ($filterType) {
            if ($filterType == 'payment') {
                $qb->andWhere('t.operationType = :operation')
                    ->setParameter('operation', 0);
            }

            if ($filterType == 'deposite') {
                $qb->andWhere('t.operationType = :operation')
                    ->setParameter('operation', 1);
            }
        }

        if ($filterExpired == 'true') {
            $qb->andWhere('t.endOfRent > :current_time OR t.endOfRent IS NULL')
                ->setParameter('current_time', new \DateTime());
        }

        $query = $qb->getQuery();


        return $query->execute();
    }

}
