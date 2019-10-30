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
        'end_date',
        'quota',
        'introduction',
        'seo',
        'electric_ticket',
        'status',
        'image',
        'host',
        'interested',
        'suitable',
        'allow_freecourse',
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
