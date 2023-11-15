<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Clears the cache - if I provide the cache name in the argument, it will only clear it, if not, it will clear the entire cache.
 * Example:
 * php bin/console app:clearCacheCommand
 * php bin/console app:clearCacheCommand menu_cache
 */
class clearCacheCommand extends Command
{
    private FilesystemAdapter $cache;

    public function __construct(FilesystemAdapter $cache)
    {
        parent::__construct();

        $this->cache = $cache;
    }
    
    protected function configure()
    {
        $this
            ->setName('app:clearCacheCommand')
            ->setDescription('This command clears all cache')
            ->addArgument('cacheName', InputArgument::OPTIONAL, 'Name of the cache to clear');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheName = $input->getArgument('cacheName');

        if ($cacheName) {
            $this->cache->delete($cacheName);
            $output->writeln(sprintf('<info>Cache "%s" cleared successfully!</info>', $cacheName));
        } else {
            $this->cache->clear();
            $output->writeln('<info>All caches cleared successfully!</info>');
        }

        return Command::SUCCESS;
    }
}