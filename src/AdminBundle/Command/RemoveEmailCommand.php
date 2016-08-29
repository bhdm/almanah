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


        $em           = $this->getContainer()->get('doctrine')->getManager();
        $pdo          = $em->getConnection();

        include_once 'emails.php';

        foreach ($emails as $mail){
            $updateDoctor   = $pdo->prepare('DELETE FROM email_evrika WHERE email="'.$mail.'"');
            $updateDoctor->execute();
        }
    }
}
