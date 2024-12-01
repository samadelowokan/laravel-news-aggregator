<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Services\ArticleFetchingService;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();

        // Allow frontend to retrieve based on search query
        if ($request->has('search')) {
            $query->where('title', 'LIKE', '%' . $request->input('search') . '%');
        }

        // Allow frontend to retrieve based on categories
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Allow frontend to retrieve based on source
        if ($request->has('source_id')) {
            $query->where('source_id', $request->input('source_id'));
        }

        // Allow frontend to retrieve based on authors
        if ($request->has('author')) {
            $query->where('author', $request->input('author'));
        }

        // Allow frontend to retrieve based on date
        if ($request->has('published_at')) {
            $query->where('published_at', $request->input('published_at'));
        }

        // Allow frontend to retrieve based on date range
        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('published_at', [
            $request->input('start_date'),
            $request->input('end_date')
          ]);
        }

        // Paginate and return in JSON format
        $articles = $query->paginate(20);

        return response()->json($articles);
    }

    public function allNews()
    {
        // get all news
        $articles = Article::orderBy('id', 'desc')->paginate(20);

        // display news
        return view('news', compact('articles'));
    }

    public function fetch(){
        session(['status' => 'empty']);

        return view('fetch');
    }

    public function fetchNow(ArticleFetchingService $articleFetchingService){
         // Set the session to show the loading message
        session(['status' => 'loading']);
            // run through the Service Provider
            // return $articleFetchingService->fetchArticles();

            // run through artisan command
            $status = Artisan::call('app:fetch-store-articles');
            
            if ($status == 0){
                session(['status' => 'success']);
            } else {
                session(['status' => 'error']);
            }
        
        return view('fetch');
    }
}