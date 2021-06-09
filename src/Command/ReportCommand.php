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

class ReportCommand extends Command
{
    private $transRepo;
    private $twig;
    private $mailer;

    public function __construct(
        TransactionRepository $transactionRepository,
        UserRepository $userRepo,
        CourseRepository $courseRepo,
        Twig $twig,
        Swift_Mailer $mailer
    ) {
        $this->transRepo = $transactionRepository;
        $this->twig = $twig;
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure()
    {
            $this
                ->setName('payment:report')
                ->setDescription('Отчет по данным об оплаченных курсах за месяц.');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $admin = $_ENV["ADMIN_EMAIL"];
        $data = $this->transRepo->reportMonth();
        $sum = 0;

        foreach ($data as $val) {
            $sum += $val['sum'];
        }


        $currentTime = date('m-d-Y', time());
        $monthAgo = date('m-d-Y', strtotime($currentTime . '-1 months'));

        $this->index($admin, $this->mailer, $data, $sum, $currentTime, $monthAgo);

        return 0;
    }

    public function index($name, \Swift_Mailer $mailer, $data, $sum, $currentTime, $monthAgo)
    {
        $html = $this->twig->render(
            'emails/report.html.twig',
            ['data' => $data,
            'sum' => $sum,
            'current' => $currentTime,
            'ago' => $monthAgo]
        );

        $message = (new \Swift_Message('Отчет об оплаченных курсах за период'))
            ->setFrom($name)
            ->setTo($name)
            ->setBody($html, 'text/html');

        $mailer->send($message);
    }
}
