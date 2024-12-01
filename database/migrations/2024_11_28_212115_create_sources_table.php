<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Prepopulate the table with default categories
        $this->seedSources();
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sources');
    }

    /**
     * Prepopulate the sources table.
     *
     * @return void
     */
    private function seedSources()
    {
        $sources = [
            'Guardian',
            'New York Times'
        ];

        foreach ($sources as $source) {
            DB::table('sources')->insert([
                'name' => $source,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};