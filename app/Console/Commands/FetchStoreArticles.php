<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ArticleFetchingService;

class FetchStoreArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:fetch-store-articles';
    protected $signature = 'app:fetch-store-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from data sources and aggregate/store them into the database';

        /**
     * The service instance.
     *
     * @var ArticleFetchingService
     */
    protected $service;

    /**
     * Create a new command instance.
     *
     * @param ArticleFetchingService $service
     * @return void
     */
    public function __construct(ArticleFetchingService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $articles = $this->service->fetchArticles();
       $this->info('Articles fetched successfully!');
    }
}
