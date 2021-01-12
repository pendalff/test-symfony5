<?php

namespace App\Command;

use App\Parser\ParserProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ParseCommand extends Command
{
    protected static $defaultName = 'app:parse';

    private ParserProcessor $processor;

    public function __construct(string $name = null, ParserProcessor $processor)
    {
        $this->processor = $processor;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Parse news from external resources');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $success = false;

        $io->note('start parsers working');

        try {
            $success = $this->processor->exec();

            $io->success('finish parsers working');
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
        }

        $io->note('end parsers working');

        return (int) !$success;
    }
}
