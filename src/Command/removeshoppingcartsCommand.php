<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ShoppingCartRepository;
use Doctrine\ORM\EntityManagerInterface;

class removeshoppingcartsCommand extends Command
{
     protected static $defaultName = 'app:remove-shoppingCarts';
     protected static $defaultDescription = '<info> Ta komenda usuwa koszyki starsze niż 24h </info>';
     private FilesystemAdapter $cache;
     private CategoryRepository $categoryRepository;

     public function __construct(ShoppingCartRepository $shoopingCartRepository, EntityManagerInterface $entityManager){
        parent::__construct();  
        $this->shoppingCartRepository = $shoopingCartRepository;    
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setHelp('
                Przykład:<info>php bin/console app:remove-shoppingCarts</info>
        ');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $shoppingCarts = $this-> shoppingCartRepository->findByDate();

        $count = 0;

        foreach($shoppingCarts as $shoppingCart){
            $this->entityManager->remove($shoppingCart);
            $count ++;
        }


        $this->entityManager->flush();

        $output->writeln("<info>Usunięto {$count} koszyków zakupowych z bazy danych które są ponad 1 dzień");
        return Command::SUCCESS;
        

    }
}