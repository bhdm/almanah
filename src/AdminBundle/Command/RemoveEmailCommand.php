<?php
namespace AdminBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveEmailCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('remove:mail');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln('delivery:unsubscribed start');
        $em           = $this->getContainer()->get('doctrine')->getManager();
        $pdo          = $em->getConnection();

        $emails = $em->getRepository('AppBundle:Unfollow')->findAll();

        foreach ($emails as $mail){
            $updateDoctor   = $pdo->prepare('DELETE FROM email_evrika WHERE email="'.$mail->getTitle().'"');
            $updateDoctor->execute();
        }

        $output->writeln('delivery:unsubscribed end');
    }
}
