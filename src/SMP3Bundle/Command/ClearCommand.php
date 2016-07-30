<?php

namespace SMP3Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends ContainerAwareCommand
{
    protected $output;

    protected function configure()
    {
        $this
                ->setName('smp3:clear')
                ->setDescription('Remove library contents')
                ->addArgument('username', InputArgument::OPTIONAL, 'Owner username')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $library = $this->getContainer()->get('LibraryService');
        $this->output = $output;
        $em = $this->getContainer()->get('doctrine')->getManager();
        $users = [];
        $username = $input->getArgument('username');
        if ($username) {
            $users = $em->getRepository('SMP3Bundle:User')->findByUsername($username);
        } else {
            $users = $em->getRepository('SMP3Bundle:User')->findAll();
        }

        foreach ($users as $user) {
            $library->clear($user);
        }
    }
}
