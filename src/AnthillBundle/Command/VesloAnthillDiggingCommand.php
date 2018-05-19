<?php

namespace Veslo\AnthillBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
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
            ->addArgument(
                'roadmap',
                InputArgument::REQUIRED,
                'Digging plan during which vacancies will be parsed from specified source'
            )
            ->addArgument(
                'query',
                InputArgument::REQUIRED,
                'Digging criteria by which vacancies will be selected for parsing'
            )
            ->addOption(
                'iterations',
                'i',
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

        $dungBeetle->dig($iterations);

        $messageComplete = str_replace('{iterations}', $iterations, 'Digging complete ({iterations} iterations).');
        $output->writeln($messageComplete);
    }
}
