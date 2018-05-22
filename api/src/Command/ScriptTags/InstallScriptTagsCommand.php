<?php

namespace App\Command\ScriptTags;

use App\Command;
use App\Repository\ShopRepository;
use Shopify\Api;
use Shopify\Object\ScriptTag;
use Shopify\Service\ScriptTagService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallScriptTagsCommand extends Command
{
    /**
     * @var ShopRepository
     */
    protected $shopRepo;

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var array
     */
    protected $sources = [];

    public function __construct(ShopRepository $shopRepo, Api $api)
    {
        parent::__construct();
        $this->shopRepo = $shopRepo;
        $this->api = $api;
    }

    protected function configure()
    {
        $this
            ->setName('script_tags:install')
            ->setDescription('Install script tags for a given store')
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'Which store are we installing script tags for', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domain = $input->getOption('domain');
        $shop = $this->shopRepo->findOneBy([
            'myshopify_domain' => $domain
        ]);
        if (is_null($shop)) {
            throw new \Exception("Shop {$domain} does not exist");
        }

        $this->api
            ->setMyshopifyDomain($shop->getMyshopifyDomain())
            ->setAccessToken($shop->getAccessToken());

        $service = new ScriptTagService($this->api);
        foreach ($this->sources as $source) {
            $scriptTag = new ScriptTag();
            $scriptTag->event = 'onload';
            $scriptTag->src = $source;
            try {
                $service->create($scriptTag);
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
}
