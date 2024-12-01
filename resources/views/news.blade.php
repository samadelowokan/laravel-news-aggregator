<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Aggregator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css')}} ">
</head>
<body>
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <!-- Header Section -->
    <div class="header">
        <h1>News Aggregator</h1>
    </div>

    <!-- News Articles Section -->
    <div class="news-container">
        @if (count($articles) > 0)
        @foreach ($articles as $article)
        <div class="news-item">
            <img src="{{ $article['image_url'] }}" alt="{{ $article['title'] }}">
            <div class="content">
                <h3>{{ $article['title'] }}</h3>
                <p>{{ Str::limit($article['description'], 100) }}</p>
                <p class="copyrights">
                    <small>{{$article->author}}</small>
                    <small>Category: {{$article->category->name}}</small>
                    <small>Source: {{$article->source->name}}</small>
                </p>
                <a target="_blank" href="{{ $article['url'] }}" class="read-more">Read more</a>
            </div>
        </div>
        @endforeach
        <div class="pagination">{{ $articles->links() }}</div>
        <div>
            <p><a href="/fetch" class="btn btn-primary btm-sm" id="fetchButton">Refresh Articles</a></p>
        </div>
        @else
        <div>
            <p class="">No posts to show</p>
            <a href="/fetch" class="btn btn-primary mt-4" id="fetch">Fetch Articles</a>

        </div>
        @endif
    </div>

    <!-- Footer Section -->
    <div class="footer mt-4">
        &copy; 2024, Samuel Adelowokan | <a href="https://github.com/samadelowokan" target="_blank">GitHub</a> | <a href="https://linkedin.com/in/samadelowokan" target="_blank">LinkedIn</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
