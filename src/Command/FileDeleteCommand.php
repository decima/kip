<?php

namespace App\Command;

use App\Services\StorageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FileDeleteCommand extends Command
{
    protected static $defaultName = 'file:delete';

    /**
     * @var StorageManager
     */
    private $storageManager;

    /**
     * FileDeleteCommand constructor.
     * @param StorageManager $storageManager
     */
    public function __construct(StorageManager $storageManager)
    {
        $this->storageManager = $storageManager;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Deleting file')
            ->addArgument('path', InputArgument::REQUIRED, 'FILENAME');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $path = $input->getArgument('path');

        $this->storageManager->dropFile($path);
    }
}
