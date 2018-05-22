<?php

namespace App\Listener\ShopInstalled;

use App\Command\ScriptTags\InstallScriptTagsCommand;
use App\Event\ShopInstalledEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CreateScriptTagsListener
{
    /**
     * @var InstallScriptTagsCommand
     */
    protected $command;

    public function __construct(InstallScriptTagsCommand $command)
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
