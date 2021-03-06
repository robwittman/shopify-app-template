<?php

namespace App\Listener\ShopInstalled;

use App\Command\Database\CreateDatabaseCommand;
use App\Event\ShopInstalledEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CreateDatabaseListener
{
    /**
     * @var CreateDatabaseCommand
     */
    protected $command;

    public function __construct(CreateDatabaseCommand $command)
    {
        $this->command = $command;
    }

    /**
     * @param ShopInstalledEvent $event
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function __invoke(ShopInstalledEvent $event)
    {
        $arguments = [
            '--domain' => $event->getShop()->getMyshopifyDomain()
        ];
        $greetInput = new ArrayInput($arguments);
        $returnCode = $this->command->run(
            $greetInput,
            new NullOutput()
        );
        return $returnCode;
    }
}
