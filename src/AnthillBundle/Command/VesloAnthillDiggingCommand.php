<?php

namespace Veslo\AnthillBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents dung (vacancies) digging process.
 */
class VesloAnthillDiggingCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('veslo:anthill:digging')
            ->setDescription('Digs some dung (vacancies) from internet and sends to queue for processing')
            ->addOption(
                'iterations',
                null,
                InputOption::VALUE_REQUIRED,
                'Maximum count of vacancies from internet to proceed during command\'s single run',
                10
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container  = $this->getContainer();
        $dungBeetle = $container->get('veslo.anthill.vacancy.dung_beetle');

        $iterations = $input->getOption('iterations');

        for ($iteration = 0; $iteration < $iterations; ++$iteration) {
            $dungBeetle->dig();
        }

        $output->writeln('Digging complete.');
    }
}
