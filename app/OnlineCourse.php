<?php

namespace App;

use App\Tag;
use App\Expert;
use App\UanalyzeModel;

final class OnlineCourse extends UanalyzeModel
{

    const TABLE = 'online_course';

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'date',
        'quota',
        'introduction',
        'status',
        'image',
        'host',
        'interested',
        'Suitable',
    ];

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function experts()
    {
        return $this->morphToMany(Expert::class, 'expertable');
    }
}
