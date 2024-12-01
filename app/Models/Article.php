<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
	use HasFactory;

	/**
     * Fillable table column names
     *
     * @var array
     */

    protected $fillable = [
		'title', 'description', 'author', 'image_url', 'source_id', 'category_id', 'published_at', 'url'
	];

	/**
     * Define relationship with the Source model.
     */
    public function source()
    {
        return $this->belongsTo(Source::class);
    }

	public function category()
    {
        return $this->belongsTo(category::class);
    }
}
