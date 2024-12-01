<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Fillable table column names
     *
     * @var array
     */

    protected $fillable = [
		'slug', 'name', 'source_id'
	];

     /**
     * Define the relationship with the Article model.
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }
}
