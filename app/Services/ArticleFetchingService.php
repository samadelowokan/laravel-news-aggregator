<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Source;
use App\Models\Category;
use Carbon\Carbon;

class ArticleFetchingService
{
    /**
	 * The News source URLs
	 *
	 * @var array
	 */
    private $sources = [
        'newsapi' => 'https://newsapi.org/v2/top-headlines',
        'guardian' => 'https://content.guardianapis.com/search',
        'nytimes' => 'https://api.nytimes.com/svc/topstories/v2/',
    ];

    /**
	 * Fetch articles from all sources
	 *
	 * @return void
	 */
    public function fetchArticles(){
        $this->fetchArticlesNewsApi($this->sources['newsapi']);
        $this->fetchArticlesGuardian($this->sources['guardian']);
        $this->fetchArticlesNyTimes($this->sources['nytimes']);
        \Log::info('Articles fetched successfully.');
    }

    /**
	 * Fetch articles from NewsAPI
     * and Store them in the database
	 *
	 * @return void
	 */
    private function fetchArticlesNewsApi($url)
    {
        $categories = Category::all();

        foreach ($categories as $category){
            sleep(1);
                try {
                    // query the api url
                    $response = Http::get($url, $this->getParams('newsapi', $category->name));
                    // store the articles
                    if ($response->ok()) {
                        $this->storeArticles($response->json(), 'newsapi', $category->name);
                    } else {
                        // Log the error response for debugging
                        \Log::error("Failed to fetch articles from News API for category {$category->name}", [
                            'status' => $response->status(),
                            'body' => $response->body(),
                        ]);
                    }
                } catch (\Throwable $e) {
                    // Catch exceptions and log them
                    \Log::error("Exception occurred while fetching articles from News API for category {$category->name}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }
    }

    /**
	 * Fetch articles from The Guardian
	 *
	 * @return void
	 */
    private function fetchArticlesGuardian($url)
    {
        try {
            // query the api url
            $response = Http::get($url, $this->getParams('guardian'));
            // store the articles
            if ($response->ok()) {
                $this->storeArticles($response->json(), 'guardian');
            } else {
                // Log the error response for debugging
                 \Log::error("Failed to fetch articles from The Guardian", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
               // Catch exceptions and log them
               \Log::error("Exception occurred while fetching articles from The Guardian", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
	 * Fetch articles from New York Times
	 *
	 * @return void
	 */
    private function fetchArticlesNyTimes($url)
    {
        $categories = Category::where('source_id', 
            Source::where('name', 'New York Times')->value('id')
        )->get();
        
        foreach ($categories as $category){
            try {
                // query the api url
                $response = Http::get($url.$category->slug.'.json', $this->getParams('nytimes'));
                if ($response->ok()) {
                    $this->storeArticles($response->json(), 'nytimes', $category->slug);
                } else {
                    // Log the error response for debugging
                    \Log::error("Failed to fetch articles from New York Times for category {$category->name}", [
                            'status' => $response->status(),
                            'body' => $response->body(),
                    ]);
                }
            } catch (\Throwable $e) {
                // Catch exceptions and log them
                \Log::error("Exception occurred while fetching articles from New York Times for category {$category->name}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    /**
     * Get parameters for the News Source and
     * Append the API Key
     * 
     * @return array
     */
    private function getParams($source, $category = null)
    {
        return match ($source) {
            'newsapi' => [
                'apiKey' => env('NEWSAPI_KEY'), 
                'category' => $category,
                'pageSize' => 5
            ], 
            'guardian' => [
                'api-key' => env('GUARDIAN_KEY'),
                'show-fields' => 'trailText,byline',
                'page-size' => 10,
            ],
            'nytimes' => ['api-key' => env('NYTIMES_KEY')],
            default => []
        };
    }

    /**
     * Store each Articles in the database
     *
     * @return void
     */    
    private function storeArticles(array $data, string $source, $category = null)
    {
        // Parse data specific to the source and store in database
        if ($source == 'newsapi'){            
            $articles = $data['articles'];

            $news_category = Category::firstOrCreate(
                ['name' => Str::title($category)]
            );

            foreach ($articles as $article){
              try {
              $news_source = Source::firstOrCreate(
                ['name' => $article['source']['name']]
              );    

              $published_at = Carbon::parse($article['publishedAt'])->setTimezone('UTC')->toDateTimeString();

              Article::firstOrCreate(
                [   'title' => $article['title'],
                ],
                [
                    'description' => $article['description'],
                    'image_url' => $article['urlToImage'] ?? null,
                    'author' => $article['author'] ?? 'Unknown',
                    'source_id' => $news_source->id,
                    'category_id' => $news_category->id,
                    'published_at' => $published_at,
                    'url' => $article['url'],
                ]
              );
              } catch (\Exception $e) {
                // Log errors specific to the article insertion
                Log::error('Error processing article: ' . $article['title'], [
                    'error' => $e->getMessage(),
                    'article' => $article
                ]);
              }
            }
        }

        if ($source == 'guardian'){
            $articles = $data['response']['results'];

            $news_source = Source::where('name','Guardian')->first();

            foreach ($articles as $article){
             try {
              $news_category = Category::firstOrCreate(
                ['name' => $article['sectionName'],
                 'source_id' => $news_source->id
                 ]
              );

              $published_at = Carbon::parse($article['webPublicationDate'])->setTimezone('UTC')->toDateTimeString();

              Article::firstOrCreate(
                [   'title' => $article['webTitle'],
                ],
                [
                    'description' => $article['fields']['trailText'],
                    'image_url' => $article['fields']['thumbnail'] ?? null,
                    'author' => $article['fields']['byline'] ?? 'Unknown',
                    'source_id' => $news_source->id,
                    'category_id' => $news_category->id,
                    'published_at' => $published_at,
                    'url' => $article['webUrl'],
                ]
              );
             } catch (\Exception $e) {
                // Log errors specific to the article insertion
                Log::error('Error processing article: ' . $article['webTitle'], [
                    'error' => $e->getMessage(),
                    'article' => $article
                ]);
             }
            }
        }
        
        if ($source == 'nytimes'){
            $articles = $data['results'];

            $news_category = Category::where('slug', $category)->first();

            foreach ($articles as $article){
             try {
              $published_at = Carbon::parse($article['published_date'])->setTimezone('UTC')->toDateTimeString();

              Article::firstOrCreate(
                [   'title' => $article['title'],
                    'source_id' => $news_category->source->id,
                ],
                [  'description' => $article['abstract'],
                    'image_url' => $article['multimedia'][1]['url'],
                    'author' => $article['byline'] ?? 'Unknown',
                    'category_id' => $news_category->id,
                    'published_at' => $published_at,
                    'url' => $article['url'],
                ]
              );
             } catch (\Exception $e) {
                // Log errors specific to the article insertion
                Log::error('Error processing article: ' . $article['title'], [
                    'error' => $e->getMessage(),
                    'article' => $article
                ]);
             }
            }
        }
    }
}
