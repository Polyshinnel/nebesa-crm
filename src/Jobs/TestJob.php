<?php


namespace App\Jobs;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestJob extends Command
{
    protected function configure()
    {
        $this->setName('test:Test')
            ->setDescription('Тестовая комманда')
            ->setHelp('Команда для тестирования интерфейса CLI');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln('Комманда успешно запущена');
        return Command::SUCCESS;
    }
}