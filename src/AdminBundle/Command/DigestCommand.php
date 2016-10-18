<?php
namespace AdminBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DigestCommand extends ContainerAwareCommand
{
    protected $sendTo; # doctor # test@test@test.ru
    protected $subject = 'Открытие портала медицинских событий';
    protected $template = 'AppBundle:Mail:delivery.html.twig';
    protected $deliveryName = 'Delivery-2';

    protected function configure()
    {
        $this->setName('delivery:mail')
            ->addArgument('sendTo', InputArgument::IS_ARRAY, 'sendTo argument');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        set_time_limit(0); # снимаем ограничение времени выполнения скрипта (в safe-mode не работает)

        $templating = $this->getContainer()->get('templating');


        $sendTo = $input->getArgument('sendTo'); # определяем, кому отправляем

        if (empty($sendTo) || count($sendTo) != 1) {
            $output->writeln('ADD ARGUMENT: test@test.ru | doctor');
            return;
        }

        $this->sendTo = $sendTo[0];
        $em           = $this->getContainer()->get('doctrine')->getManager();
        $pdo          = $em->getConnection();

        # тестовый режим
        if (strpos($this->sendTo, '@') !== false) {

            $html =  $templating->render($this->template, ['email' => $this->sendTo, 'id' => '2']);
            $email = $this->sendTo;
            $to    = $this->sendTo;
            $error = $this->send($email, $to, $html, $this->subject, true);
            $output->writeln($error);
            $output->writeln("... sent to <{$this->sendTo}>");

            return;
        }


        $total = $em->createQuery('
			SELECT COUNT(e.id)
			FROM AppBundle:Email2 e
			WHERE e.sent = false
		')->getSingleScalarResult();

        # рассылка по 75 пользователям за цикл
//        for ($i = 0 ; $i < $total; $i+=75) {

            $doctors = $em->createQuery('
			SELECT e.id, e.email
			FROM AppBundle:Email2 e
			WHERE e.sent = false			
            ORDER BY e.id ASC            
		')      ->setmaxresults(500)
                ->getResult();
//                ->setFirstResult($i)


//            Находим последние публикации

            $publications = $em->getRepository('AppBundle:Publication')->findBy(['digest' => true, 'enabled' => true],['id' => 'DESC'],4);
            $events = $em->getRepository('AppBundle:Event')->findForDigest();

            for ($j = 0 , $countdoctors= count($doctors); $j < $countdoctors; $j++) {
//                dump($doctors[$j]);
                $updateDoctor   = $pdo->prepare('UPDATE email_evrika SET sent=1 WHERE id = '.$doctors[$j]['id']);
                $updateDoctor->execute();

                $html = $templating->render($this->template, array(
                    'email' => $doctors[$j]['email'],
                    'id' => $doctors[$j]['id'],
                    'publications' => $publications,
                    'events' => $events,
                ));
                $email = $doctors[$j]['email'];
                $to    = $doctors[$j]['email'];

                $error = $this->send($email, $to, $html, $this->subject);
                $output->writeln($error);
                $output->writeln($email);
            }
//            sleep(random_int(60,180));
//        }

    }

    public function send($email, $to, $body, $subject, $local = false)
    {
        $mail = new \PHPMailer();

        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->SMTPDebug = 0;
        $mail->SMTPSecure = 'tls';
        $mail->CharSet  = 'UTF-8';
        $mail->From     = 'mailer@medalmanah.ru';
        $mail->FromName = 'Альманах медицинских событий';
        $mail->Host     = 'localhost';
        $mail->Username = 'mailer';
        $mail->Password = '3245897';
        $mail->SMTPAuth = false;
        $mail->Port     = 25;
        $mail->Subject  = $subject;
        $mail->Body     = $body;
        $mail->addAddress($email, $to);
        $mail->addCustomHeader('Precedence', 'bulk');
        $mail->addCustomHeader('List-Unsubscribe', "<https://medalmanah.ru/unfollow?email=$email>");
        $mail->addCustomHeader('X-Postmaster-Msgtype', "firstDelivery");

        return $mail->send() ? null : $mail->ErrorInfo;
    }
}
