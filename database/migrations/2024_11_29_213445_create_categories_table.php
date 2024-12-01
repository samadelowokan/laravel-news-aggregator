<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('name');
            $table->foreignId('source_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Prepopulate the table with default categories
        $this->seedCategories();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }

    /**
     * Prepopulate the categories table.
     *
     * @return void
     */
    private function seedCategories()
    {
        $guardian_categories = [
            'General',
            'Business',
            'Entertainment',
            'Health',
            'Science',
            'Sports',
            'Technology',
        ];

        $nytimes_categories = [
            'arts' => 'Arts', 
            'automobiles' => 'Automobiles', 
            'books/review' => 'Books/Review', 
            'business' => 'Business', 
            'fashion' => 'Fashion', 
            'food' => 'Food', 
            'health' => 'Health', 
            'home' => 'Home', 
            'insider' => 'Insider', 
            'magazine' => 'Magazine', 
            'movies' => 'Movies', 
            'nyregion' => 'NY Religion', 
            'obituaries' => 'Obituaries', 
            'opinion' => 'Opinion', 
            'politics' => 'Politics', 
            'realestate' => 'Real Estate', 
            'science' => 'Science', 
            'sports' => 'Sports', 
            'sundayreview' => 'Sunday Review', 
            'technology' => 'Technology', 
            'theater' => 'Theater', 
            't-magazine' => 'T Magazine', 
            'travel' => 'Travel', 
            'upshot' => 'Upshot', 
            'us' => 'US', 
            'world' => 'World',
        ];

        $sources = DB::table('sources')->get();

        foreach ($guardian_categories as $category) {
            DB::table('categories')->insert([
                'slug' => null,
                'name' => $category,
                'source_id' => $sources->firstWhere('name', 'Guardian')->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($nytimes_categories as $slug => $category) {
            DB::table('categories')->insert([
                'slug' => $slug,
                'name' => $category,
                'source_id' => $sources->firstWhere('name', 'New York Times')->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};