<?php
namespace Evrika\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ErrorEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('delivery:error')
            ->setDescription('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('---  started!');

        $container = $this->getContainer();
        $em        = $container->get('doctrine')->getManager();
        $emails    = array();

        //$dir   = __DIR__;
        $dir   = '/var/log/exim4';
        $files = scandir($dir);

        # unzip mainlog archieves
        foreach ($files as $file) {
            if (preg_match('/mainlog(.*)\.gz/', $file)) {
                exec('gunzip ' . $dir. DIRECTORY_SEPARATOR . $file);
            }
        }

        foreach ($files as $file) {
            if ($file == 'mainlog') {
                $lines = file($dir . DIRECTORY_SEPARATOR . $file);

                foreach ($lines as $line) {
                    $matches = array();

                    if (preg_match_all('/([^@\s]++@\S++) .*(-53|110|111)/i', $line, $matches)) {
                        $email = $matches[1][0];
                        $error = $matches[1][1];

//                        if (strpos($email, '@medalmanah.ru') === false) {
//                            $emails[] = $email;
//                        }

                        $output->writeln('+++' . $email . ' '.$error);
                    }
                }
            }
        }

//        if (!empty($emails)) {
//            $updateQuery = $em->createQuery('
//				UPDATE EvrikaMainBundle:Doctor d
//				SET d.subscriber = FALSE
//				WHERE d.username = :email
//			');
//
//            foreach ($emails as $email) {
//                $updateQuery->setParameter('email', $email)->execute();
//            }
//        }

//        $output->writeln('+++ evrika:email_not_found unsubscribed ' . count($emails) . ' users');
    }
}