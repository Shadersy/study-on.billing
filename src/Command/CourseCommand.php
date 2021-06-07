<?php

namespace App\Command;

use App\Repository\CourseRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Service\Twig;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CourseCommand extends Command
{

    private $transRepo;
    private $userRepo;
    private $courseRepo;
    private $twig;
    private $mailer;

    public function __construct(
        TransactionRepository $transactionRepository,
        UserRepository $userRepo,
        CourseRepository $courseRepo,
        Twig $twig,
        Swift_Mailer $mailer
    )
    {
        $this->transRepo = $transactionRepository;
        $this->userRepo = $userRepo;
        $this->courseRepo = $courseRepo;
        $this->twig = $twig;
        $this->mailer = $mailer;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('payment:ending:notification')
            ->setDescription('Уведомление об окончании срока аренды.');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

       $transactions = $this->transRepo->endingNotification();

       $messages = [];

        foreach ($transactions as &$value) {
          $messages[] = [
              "EMAIL" => $this->userRepo->find($value->getUsername())->getEmail(),
              "COURSE" => $courses[] = $this->courseRepo->findBy(['id' => $value->getCourse()->getId()]),
              "TRANSACTION" => $value->getEndOfrent()];
        }


        foreach($messages as $key => $val)
        {
            $messages[$val['EMAIL']][] = $val;
            unset($messages[$key]);
        }

        foreach($messages as $key => $val)
        {
            $this->index($key, $this->mailer, $val);
        }


        return 0;
    }

    public function index($name, \Swift_Mailer $mailer, $data)
    {
        $html = $this->twig->render(
            'emails/email.html.twig',
            ['data' => $data]
        );

        $message = (new \Swift_Message('Уважаемый клиент! 
        У вас есть курсы, срок аренды которых подходит к концу: '))
            ->setFrom('send@example.com')
            ->setTo($name)
            ->setBody($html, 'text/html');

       $mailer->send($message);
    }
}