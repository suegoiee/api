<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    
    protected $fillable = [
        'title','content','top','posted_at','status','slug'
    ];
    private function id(){
    	return $this->id;
    }
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable')->withTimestamps();
    }

    public function generateUniqueSlug()
    {
    	$value = $this->title;
        $slug = $originalSlug = str_slug($value);
        $counter = 0;

        while ($this->slugExists($slug, $this->exists ? $this->id() : null)) {
            $counter++;
            $slug = $originalSlug.'-'.$counter;
        }

        return $slug;
    }

    public function slugExists(string $slug, int $ignoreId = null): bool
    {
        $query = $this->where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->count();
    }
}
