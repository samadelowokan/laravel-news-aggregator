## About this Laravel PHP Backend News Aggregate Application

This application gets News articles data every hour from NewsAPI, The Guardian, New York Times and BBC News, and stores it in the database. The database used is MySQL.

Laravel uses the installed GuzzleHTTP, which will handle the HTTP Requests

## Configuration

1. Clone repository and run necessary Laravel setups

```bash
composer install
npm install
```

2. Update the Database Variables in the .env file:
   DB_DATABASE, DB_USERNAME and DB_PASSWORD

3. Update the API variables in the .env file:
   NEWS_API_KEY, GUARDIAN_KEY, NYTIMES_KEY and BBC_KEY

4. Run the migration

```bash
php artisan migrate
```

5. To fetch articles from News APIs and store in the database, visit https://localhost/fetch or run the following from the command line:

```bash
php artisan schedule:work
php artisan app:fetch-store-articles
```

6. To schedule News Articles for automatic updates, add this to your server cronjob setting once

```
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

7. For troubleshooting, check the storage/logs/laravel.log file

## Testing

Full contents are not displayed for NewsAPI, only excerpts or short descriptions, with a URL that leads to the full content. To display full content, a paid subscription / commercial license is needed.

In order to test each News sources of the API, try the following Example URLs:

https://newsapi.org/v2/top-headlines?category=technology&apiKey=c8c280cea80844399d1c87b8c62e4ede

https://content.guardianapis.com/search?api-key=54f87939-0d21-4e44-a68d-cd504d88a97c

https://api.nytimes.com/svc/topstories/v2/world.json?&api-key=xQG3eTlyGIhuP6VD1PBBhhb3ofv3BhX8

### My links

-   **[LinkedIn Profile](https://linkedin.com/in/samadelowokan/)**
-   **[GitHub Repository](https://github.com/samadelowokan)**

Thank you
