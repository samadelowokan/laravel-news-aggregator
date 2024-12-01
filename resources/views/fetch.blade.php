<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Aggregator</title>
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

    <div class="news-container d-block" style="justify-items:center;">
        <!-- Success Message -->
        @if(session('status') === 'success')
        <div class="alert alert-success">
            <strong>Success!</strong> Articles fetched successfully.
        </div>
        @endif

        <!-- Error Message -->
        @if(session('status') === 'error')
        <div class="alert alert-danger">
            <strong>Error!</strong> Something went wrong. Please try again.
        </div>
        @endif

        <!-- Loading Message -->
        @if(session('status') === 'loading')
        <div class="alert alert-info">
            <strong>Loading...</strong> We are fetching articles for you.
        </div>
        @endif

        <p>You are welcome!</p>

        <p><a href="/fetchNow" class="btn btn-primary" id="fetchButton">Fetch Articles</a></p>

        <p><a href="/" class="btn btn-primary" id="fetchButton">Go to Articles</a></p>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        &copy; 2024, Samuel Adelowokan | <a href="https://github.com/samadelowokan" target="_blank">GitHub</a> | <a href="https://linkedin.com/in/samadelowokan" target="_blank">LinkedIn</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Periodically refresh the page every 5 seconds to check the status
        setInterval(function() {
            location.reload();
        }, 5000); // Adjust the interval as needed

        // Button functionality
        document.getElementById('fetchButton').addEventListener('click', function() {
            // Hide the preloader after clicking
            document.getElementById('preloader').style.display = 'flex';
        });

    </script>
</body>
</html>
