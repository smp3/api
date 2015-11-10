<?php

namespace SMP3Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DiscoverCommand extends ContainerAwareCommand {

    protected $output;

    protected function configure() {
        $this
                ->setName('smp3:discover')
                ->setDescription('Discover audio files')
                ->addArgument('username', InputArgument::OPTIONAL, 'Owner username')
                ->addArgument('debug', InputArgument::OPTIONAL, 'Debug mode')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $library = $this->getContainer()->get('LibraryService');
        $this->output = $output;
        $em = $this->getContainer()->get('doctrine')->getManager();
        $users = [];
        $username = $input->getArgument('username');
        
        if ($username) {
            $output->writeln("Discovering files for $username");
            $users = $em->getRepository('SMP3Bundle:User')->findByUsername($username);
        } else {
            $output->writeln("Discovering files for all users");
            $users = $em->getRepository('SMP3Bundle:User')->findAll();
        }

        foreach($users as $user) {
            if($input->getArgument('debug')) {
                $library->setDebug(true);
            }
            $return = $library->discover($user);
            $this->output->writeln("{$return->counter} files discovered in {$return->time} \n");
        }

    }

}
