<?php

namespace App\Command\Webhooks;

use App\Command;
use App\Repository\ShopRepository;
use Shopify\Api;
use Shopify\Object\Webhook;
use Shopify\Service\WebhookService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateWebhooksCommand extends Command
{
    protected $api;

    protected $webhooks = [];

    protected $service;

    protected $shopRepository;

    public function __construct(ShopRepository $shopRepo, Api $api)
    {
        parent::__construct();
        $this->api = $api;
        $this->shopRepository = $shopRepo;
    }

    protected function configure()
    {
        $this
            ->setName('webhooks:install')
            ->setDescription('Install webhooks for a Shopify store')
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'Which store to install for', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domain = $input->getOption('domain');
        $shop = $this->shopRepository->findOneBy([
            'myshopify_domain' => $domain
        ]);
        if (is_null($shop)) {
            throw new \Exception("Shop {$domain} does not exist");
        }
        $this->api
            ->setMyshopifyDomain($shop->getMyshopifyDomain())
            ->setAccessToken($shop->getAccessToken());
        $this->service = new WebhookService($this->api);
        foreach ($this->webhooks as $event) {
            foreach ($event as $destination) {
                $this->createWebhook($event, $destination);
            }
        }
    }

    protected function createWebhook($event, $destination)
    {
        $webhook = new Webhook();
        $webhook->topic = $event;
        $webhook->address = $destination;
        try {
            $this->service->create($webhook);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}
