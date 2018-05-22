<?php

namespace App\Listener\ShopInstalled;

use App\Command\Webhooks\CreateWebhooksCommand;
use App\Event\ShopInstalledEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CreateWebhooksListener
{
    /**
     * @var CreateWebhooksCommand
     */
    protected $command;

    public function __construct(CreateWebhooksCommand $command)
    {
        $this->command = $command;
    }

    /**
     * @param ShopInstalledEvent $event
     * @return int
     * @throws \Exception
     */
    public function invoke(ShopInstalledEvent $event)
    {
        $shop = $event->getShop();
        $arguments = [
            '--domain' => $shop->getMyshopifyDomain()
        ];
        $greetInput = new ArrayInput($arguments);
        $returnCode = $this->command->run(
            $greetInput,
            new NullOutput()
        );
        return $returnCode;
    }
}
