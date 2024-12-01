<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    /**
     * Fillable table column names
     *
     * @var array
     */

    protected $fillable = [
		'slug', 'name'
	];

    /**
     * Define the relationship with the Article model.
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    
}
